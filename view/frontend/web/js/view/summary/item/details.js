/**
 * Roman Yurkhanov
 *
 * Email :: ferrumdp@gmail.com
 * Linkedin :: https://www.linkedin.com/in/roman-yurkhanov-322035122/
 *
 * Copyright 2024-present Roman Yurkhanov. All rights reserved.
 * See license.txt for license details.
 */
 
define(
    [
        'uiComponent'
    ],
    function (Component) {
        "use strict";
        var quoteItemData = window.checkoutConfig.quoteItemData;
        return Component.extend({
            defaults: {
                template: 'RomanYurkhanov_Dealer/summary/item/details',
            },
            quoteItemData: quoteItemData,
            getValue: function (quoteItem) {
                return quoteItem.name;
            },
            getDealer: function (quoteItem) {
                var item = this.getItem(quoteItem.item_id);
                return item.dealer;
            },
            getItem: function (item_id) {
                var itemElement = null;
                _.each(this.quoteItemData, function (element, index) {
                    if (element.item_id == item_id) {
                        itemElement = element;
                    }
                });
                return itemElement;
            }
        });
    }
);
