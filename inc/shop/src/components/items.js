import './items.scss';
import Item from './item';
import {useSelect} from "@wordpress/data";
import {STORE_NAME} from "../store";

export default function Items() {
	const { selectedCategory, items, wapuu } = useSelect( ( select ) => {
		return {
			items: select( STORE_NAME ).getItems(),
			selectedCategory: select( STORE_NAME ).getSelectedCategory(),
			wapuu: select( STORE_NAME ).getWapuu(),
		};
	} );

	return (
		<div className="wapuugotchi_shop__items">
			{Object.keys( items?.[selectedCategory] ).map( ( uuid ) => (
				<Item key={uuid} uuid={uuid} item={items?.[selectedCategory]?.[uuid]} />
			) )}
		</div>
	);
}
