import './navigation.scss';
import {dispatch, useSelect} from "@wordpress/data";
import {STORE_NAME} from "../../store/onboarding";
import {useEffect} from "@wordpress/element";
import GlobalNavigation from "./global-navigation";

export default function Navigation() {
	const { index, animated, pageName, pageConfig, globalConfig } = useSelect( ( select ) => {
		return {
			index: select( STORE_NAME ).getIndex(),
			pageName: select( STORE_NAME ).getPageName(),
			pageConfig: select( STORE_NAME ).getPageConfig(),
			globalConfig: select( STORE_NAME ).getGlobalConfig(),
			animated: select( STORE_NAME ).getAnimated(),
		};
	} );

	useEffect(() => {
			window.onkeyup = (e) => {
				if( isGutenbergGuideActive() === true) {
				e.preventDefault();
				e.stopPropagation();
				switch (e.key) {
					case 'ArrowRight':
						nextStep();
						break;
					case 'ArrowLeft':
						lastStep();
						break;
					case 'Escape':
						stop();
						break;
					case ' ':
						startAnimation();
						break;
				}
			};
		}
	});

	const isGutenbergGuideActive = () => {
		if( wp?.data?.select( 'core/edit-post' )?.isFeatureActive( 'welcomeGuide' ) !== true
			//&& wp?.data?.select( 'core/preferences' )?.get( 'core/edit-widgets', 'welcomeGuide' ) !== true
		) {
			return true;
		} else {
			return false;
		}
	}

	const getIndex = () => {
		const pageKeyList = Object.keys(pageConfig);
		return pageKeyList?.indexOf(index.toString());
	}

	const fadeOut = () => {
		let fadeOut = document.getElementById("wapuugotchi__avatar");
		if(!fadeOut) {
			return false;
		}
		let fadeOutEffect = setInterval(function () {
			if (!fadeOut.style.opacity) {
				fadeOut.style.opacity = 1;
			}
			if (fadeOut.style.opacity > 0) {
				fadeOut.style.opacity -= 0.1;
			} else {
				clearInterval(fadeOutEffect);
			}
		}, 10);

		return true;
	}
	fadeOut();

	const nextStep = () => {
		const keyList = Object.keys(pageConfig)
		const indexPosition = keyList?.indexOf(index.toString())

		if (indexPosition >= 0 && keyList?.length > indexPosition) {
			let nextIndex = keyList[indexPosition + 1];
			let button = document.querySelector('button.wapuugotchi_onboarding__navigation_next span');
			if (pageConfig?.[nextIndex] !== undefined) {
				if(button.classList.contains('disabled') === false) {
					dispatch(STORE_NAME).setIndex(nextIndex);
				}
			} else {
				nextPage();
			}
		}
	}

	const lastStep = () => {
		let keyList = Object.keys(pageConfig)
		let indexPosition = keyList?.indexOf(index.toString())

		if (indexPosition > 0) {
			let nextIndex = keyList[indexPosition - 1];
			let button = document.querySelector('button.wapuugotchi_onboarding__navigation_last span');
			if (pageConfig?.[nextIndex] !== undefined) {
				if(button.classList.contains('disabled') === false) {
					dispatch(STORE_NAME).setIndex(nextIndex);
				}
			}
		}
	}

	const nextPage = () => {
		const globalKeyList = Object.keys(globalConfig)
		const pagePosition = globalKeyList?.indexOf(pageName)

		if (pagePosition >= 0 && globalKeyList?.length > pagePosition) {
			let nextPage = globalKeyList[pagePosition + 1];
			redirectToPage( nextPage )
		}
	}

	const redirectToPage = ( nextPageName = '' ) => {
		const currentPage = globalConfig?.[pageName]?.['page']
		const nextPage = globalConfig?.[nextPageName]?.['page']
		if ( currentPage === undefined || nextPage === undefined ) {
			stop()
			return false;
		}

		let url = new URL( window.location );
		let urlString = url.origin;

		if( url?.pathname?.includes('.php') ) {
			let urlSplit = currentPage.split('?', 1)
			const pathname = url.pathname.replace(urlSplit, nextPage)
			urlString += pathname
		} else {
			urlString += url.pathname + nextPage
		}

		let redirect = new URL(urlString)
		redirect.searchParams.append( 'onboarding_mode', 'tour' );
		window.location = redirect.toString()
		return true;
	}

	const stop = () => {
		const url = new URL( window.location );
		url.searchParams.delete( 'onboarding_mode' );
		window.location = url.toString();
	}

	const startAnimation = () => {
		if (getTargetsCount() > 1 ) {
			dispatch(STORE_NAME).setAnimated(true);
			handleLoader()
		}
	}

	const getTargetsCount = () => {
		let count = 0;
		if ( Array.isArray(pageConfig?.[index]?.targets ) ) {
			count = pageConfig[index].targets.length
		}
		return count
	}



	const handleLoader = () => {
		// Loader handling
		let loader = document.querySelector('.wapuugotchi_onboarding__loader');
		let nextButton = document.querySelector('button.wapuugotchi_onboarding__navigation_next span');
		let lastButton = document.querySelector('button.wapuugotchi_onboarding__navigation_last span');
		let playButton = document.querySelector('button.wapuugotchi_onboarding__navigation_play');
		if ( Number.isInteger(pageConfig?.[index]?.freeze) && pageConfig?.[index]?.freeze > 0) {
			//show loader and disable next and last button
			loader.style.display = 'block';
			nextButton.classList.add('disabled')
			lastButton.classList.add('disabled')
			playButton.classList.add('invisible')
			setTimeout(function() {
				//hide loader and enable next and last button
				loader.style.display = 'none';
				nextButton.classList.remove('disabled')
				lastButton.classList.remove('disabled')
				playButton.classList.remove('invisible')
			}, pageConfig[index].freeze);
		}
	}

	return (
		<>
			<div id="wapuugotchi_onboarding__navigation">
				<button className='wapuugotchi_onboarding__navigation_stop' onClick={stop}><span
					className="dashicons dashicons-no"></span></button>
				<button className='wapuugotchi_onboarding__navigation_last' onClick={lastStep}><span
					className={'dashicons dashicons-controls-back' + (getIndex() === 0 ? ' disabled' : '')}></span>
				</button>
				<button className='wapuugotchi_onboarding__navigation_play' onClick={startAnimation}><span
					className={'dashicons dashicons-controls-play' + (getTargetsCount() <= 1 ? ' disabled' : '')}></span>
				</button>
				<button className='wapuugotchi_onboarding__navigation_next' onClick={nextStep}><span
					className='dashicons dashicons-controls-forward'></span></button>
				<div className="wapuugotchi_onboarding__loader"></div>
				<GlobalNavigation />
			</div>
		</>
	);
}
