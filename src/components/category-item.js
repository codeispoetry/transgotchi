import { useCallback } from "@wordpress/element";

import { STORE_NAME } from "../store";
import { useSelect, dispatch } from "@wordpress/data";

import priceTag from "./category-item-pricetag.svg";
import "./category-item.scss";

export default function CategoryItem({ selectedCategory, item }) {
	const { items, wapuu, balance, restBase } = useSelect((select) => {
		return {
			wapuu: select(STORE_NAME).getWapuu(),
			items: select(STORE_NAME).getItems(),
			categories: select(STORE_NAME).getCategories(),
			balance: select(STORE_NAME).getBalance(),
			restBase: select(STORE_NAME).getRestBase(),
		};
	});

	const __handleItem = () => {
		const data_key = item.meta.key;

		if (items[selectedCategory] && items[selectedCategory][data_key]) {
			let item_data = items[selectedCategory][data_key];
			if ( item_data.meta?.price > 0 ) {
				if( item_data.meta.price <= balance ) {
					dispatch(STORE_NAME).purchaseItem(item_data);
				}
			} else {
				const category_data = wapuu.char[selectedCategory];
				if (category_data) {
					if (category_data.key.includes(data_key)) {
						if (!category_data.required) {
							let index = category_data.key.indexOf(data_key);
							category_data.key.splice(index, 1);
							dispatch(STORE_NAME).setWapuu(wapuu);
						}
					} else {
						if (category_data.count < category_data.key.length + 1) {
							category_data.key.pop();
						}
						category_data.key.push(data_key);
						dispatch(STORE_NAME).setWapuu(wapuu);
					}
				}
			}
		}
	};

	// create a cached version of handleItem updated only when selectedCategory or item changes
	const handleItem = useCallback(__handleItem, [selectedCategory, item]);

	return (
		<div onClick={handleItem} className={item.classes}>
			<img className="wapuu_card__item_img" src={item.preview} />
			{item.tooltip && (
				<div className="wapuu_card__item_pricetag">
					<img src={priceTag} />
					<span>{item.tooltip}</span>
				</div>
			)}
		</div>
	);
}
