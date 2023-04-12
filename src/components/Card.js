import {useEffect, useState} from '@wordpress/element';
import Categories from './Categories';
import './Card.css'
import {STORE_NAME} from "../store";

import {useSelect, dispatch} from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

const Card = (props) => {
	const [selectedCategory, setSelectedCategory] = useState('fur');
	const {items, categories, wapuu} = useSelect(select => {
		return {
			wapuu: select(STORE_NAME).getWapuu(),
			items: select(STORE_NAME).getItems(),
			categories: select(STORE_NAME).getCategories(),
		}
	});

	const handleItem = (event) => {
		const wapuu_data = wapuu;

		const data_key = event.target.getAttribute('data-key');
		const category = event.target.getAttribute('category');

		if( items[category] === undefined || items[category][data_key] === undefined ) {
			return;
		}

		let item_data = items[category][data_key];
		if(item_data.meta.price > 0){
			return;
		}

		const category_data = wapuu_data.char[category];
		if (category_data === undefined) {
			return;
		}

		if (category_data.key.includes(data_key)) {
			if (category_data.required > 0) {
				return;
			}
			let index = category_data.key.indexOf(data_key)
			category_data.key.splice(index, 1)
		} else {
			if (category_data.count < category_data.key.length + 1) {
				category_data.key.pop()
			}
			category_data.key.push(data_key)
		}

		dispatch(STORE_NAME).setWapuu(wapuu_data);

		document.querySelectorAll('.wapuu_card__item').forEach(item => {
			if (item.getAttribute('category') === category) {
				let key = item.getAttribute('data-key');
				if (category_data.key.includes(key)) {
					item.classList.add('selected')
				} else {
					item.classList.remove('selected')
				}
			}
		});
		/*
		Object.keys(props.collection).map(category => {
		  if (props.collection[category] !== undefined) {
			props.collection[category].map(item => {
			  if (item.key === data_key) {
				let char = props.wapuu.char;
				let index = char[category].key.indexOf(data_key);
				if (index > -1) { // remove
				  if (char[category].required === 1 && char[category].key.length === 1) {
					console.info('Required 1 item of this category')
				  } else {
					char[category].key.splice(index, 1);
				  }
				} else if (char[category].key.length < props.wapuu.char[category].count) { // add
				  char[category].key.push(data_key)
				} else if (char[category].key.length === 1 && char[category].count === 1) { // replace
				  char[category].key.splice(0, 1)
				  char[category].key.push(data_key)
				}

				const wapuu = {
				  ...props.wapuu,
				  char: char
				}

				props.onChangeWapuuConfig(wapuu);
			  }
			})
		  }
		})
		*/
	}

	const getItemList = () => {
		let itemList = [];

		if (items[selectedCategory] !== undefined) {
			Object.values(items[selectedCategory]).map(item => {
				let classes = 'wapuu_card__item';
				if (wapuu.char[selectedCategory] !== undefined
					&& wapuu.char[selectedCategory].key.includes(item.meta.key)) {
					classes += ' selected';
				} else if (item.meta.price > 0) {
					classes = 'wapuu_card__item wapuu_card__locked';
				}

				let tooltip = undefined
				if (item.meta.price > 0) {
					tooltip = item.meta.price;
				}

				itemList.push({...item, classes: classes, tooltip: tooltip})
			})
		}

		// Sort ItemList to show locked items at the end
		itemList.sort((a, b) => {
			if (a.meta.price > b.meta.price) {
				return 1;
			}
			if (a.meta.price < b.meta.price) {
				return -1;
			}
			return 0;
		});

		return itemList;
	}

	return (
		<div className='wapuu_card'>
			<div className='wapuu_card__categories'>
				{
					Object.keys(categories).map(index => <Categories key={index} slug={index}
																	 category={categories[index]}
																	 handleSelection={setSelectedCategory}
																	 selectedCategory={selectedCategory}/>)
				}
			</div>
			<div className='wapuu_card__items'>
				{
					getItemList().map(item => {
						return (
							<div onClick={handleItem} category={selectedCategory} key={item.meta.key}
								 data-key={item.meta.key} className={item.classes}>
								<img className='wapuu_card__item_img' src={item.preview}/>
								{
									item.tooltip !== undefined ?
										<div className="wapuu_card__item_pricetag">
											<svg width="17" height="12" viewBox="0 0 17 12" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M8.89647 4.23926C6.75971 4.23926 5.01611 5.98289 5.01611 8.11962C5.01611 10.2564 6.75974 12 8.89647 12C11.0332 12 12.7768 10.2564 12.7768 8.11962C12.7768 5.98298 11.0332 4.23926 8.89647 4.23926ZM10.7426 8.64954C10.4521 8.64954 10.2128 8.41023 10.2128 8.11965C10.2128 7.4017 9.6144 6.80337 8.89647 6.80337C8.60589 6.80337 8.36659 6.56406 8.36659 6.27349C8.36659 5.9829 8.6059 5.7436 8.89647 5.7436C10.2128 5.7436 11.2896 6.82056 11.2896 8.13671C11.2896 8.41025 11.0503 8.64957 10.7426 8.64957V8.64954Z" fill="white"/>
												<path d="M6.24698 3.11111C6.33247 3.3162 6.53756 3.43593 6.74264 3.43593C6.81097 3.43593 6.87941 3.41888 6.94774 3.40171C7.22127 3.28199 7.35803 2.97435 7.23832 2.70082L6.72547 1.47004C6.60575 1.19651 6.29812 1.07691 6.02458 1.17946C5.75105 1.29917 5.61429 1.60681 5.734 1.88035L6.24698 3.11111Z" fill="white"/>
												<path d="M5.28967 3.74355L3.92211 2.37599C3.71701 2.17089 3.37503 2.17089 3.16994 2.37599C2.96484 2.58108 2.96484 2.92306 3.16994 3.12816L4.53749 4.49572C4.64004 4.59826 4.77681 4.64954 4.91356 4.64954C5.05032 4.64954 5.1871 4.59826 5.28963 4.49572C5.49473 4.29062 5.49476 3.96583 5.28967 3.74355Z" fill="white"/>
												<path d="M3.88783 5.47L2.65706 4.95715C2.38353 4.83743 2.07589 4.9742 1.95617 5.24773C1.83645 5.52126 1.97322 5.8289 2.24675 5.94862L3.47753 6.46147C3.54585 6.49569 3.6143 6.49569 3.68262 6.49569C3.88772 6.49569 4.09291 6.37598 4.17829 6.17087C4.28096 5.89734 4.16125 5.57253 3.88783 5.47Z" fill="white"/>
												<path d="M3.78536 8.11961C3.78536 7.82902 3.54605 7.58972 3.25547 7.58972L1.30674 7.5896C1.01616 7.5896 0.776855 7.82891 0.776855 8.11949C0.776855 8.41006 1.01617 8.64937 1.30674 8.64937L3.25547 8.64949C3.54605 8.64949 3.78536 8.41018 3.78536 8.11961Z" fill="white"/>
												<path d="M3.47752 9.79477L2.24675 10.3076C1.97321 10.4273 1.83646 10.735 1.95616 11.0085C2.04166 11.2136 2.24675 11.3333 2.45183 11.3333C2.52016 11.3333 2.5886 11.3163 2.65692 11.2991L3.8877 10.7863C4.16123 10.6665 4.29799 10.3589 4.17828 10.0854C4.05869 9.81184 3.75106 9.67507 3.47752 9.79477Z" fill="white"/>
												<path d="M15.546 10.2906L14.3153 9.7777C14.0417 9.65799 13.7341 9.79475 13.6144 10.0683C13.4947 10.3418 13.6314 10.6495 13.905 10.7692L15.1357 11.282C15.2041 11.3163 15.2725 11.3163 15.3408 11.3163C15.5459 11.3163 15.7511 11.1965 15.8365 10.9914C15.9392 10.7179 15.8196 10.4103 15.546 10.2906Z" fill="white"/>
												<path d="M16.4864 7.58984H14.5377C14.2471 7.58984 14.0078 7.82916 14.0078 8.11973C14.0078 8.41031 14.2471 8.64962 14.5377 8.64962H16.4694C16.7599 8.64962 16.9993 8.41031 16.9993 8.11973C17.0163 7.82927 16.777 7.58984 16.4864 7.58984H16.4864Z" fill="white"/>
												<path d="M13.6145 6.17102C13.7 6.37611 13.9051 6.49584 14.1102 6.49584C14.1785 6.49584 14.2469 6.47879 14.3153 6.46162L15.546 5.94876C15.8196 5.82905 15.9563 5.52141 15.8366 5.24787C15.7169 4.97434 15.4093 4.85474 15.1357 4.95729L13.905 5.47014C13.6317 5.57269 13.512 5.89748 13.6145 6.17102Z" fill="white"/>
												<path d="M14.6398 2.37599C14.4347 2.17089 14.0927 2.17089 13.8876 2.37599L12.52 3.74355C12.3149 3.94864 12.3149 4.29062 12.52 4.49572C12.6226 4.59826 12.7593 4.64954 12.8961 4.64954C13.0329 4.64954 13.1696 4.59826 13.2722 4.49572L14.6397 3.12816C14.8448 2.92295 14.8449 2.58109 14.6398 2.37599Z" fill="white"/>
												<path d="M10.8451 3.40182C10.9134 3.43604 10.9819 3.43604 11.0502 3.43604C11.2553 3.43604 11.4605 3.31632 11.5459 3.11122L12.0587 1.88045C12.1784 1.60691 12.0417 1.29928 11.7681 1.17956C11.4946 1.05984 11.187 1.19661 11.0672 1.47014L10.5544 2.70092C10.452 2.97445 10.5716 3.28222 10.8451 3.40182Z" fill="white"/>
												<path d="M8.89659 3.0085C9.18717 3.0085 9.42647 2.76919 9.42647 2.47861V0.529887C9.42647 0.239302 9.18716 0 8.89659 0C8.60601 0 8.3667 0.239311 8.3667 0.529887V2.46155C8.3667 2.76918 8.60601 3.0085 8.89659 3.0085Z" fill="white"/>
											</svg>
											<span>{item.tooltip}</span>
										</div>
										: ''
								}
							</div>
						)
					})
				}
			</div>
		</div>
	);
}

export default Card;
