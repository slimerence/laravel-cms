import StripeConfig from './stripe-config';
export default class Store {
    constructor(mode, country, currency, stripeCountry, stripePublishableKey) {
        this.mode = mode;
        let data = {};
        if(mode === 'test'){
            data.country = 'US';
            data.currency = 'eur';
            data.stripeCountry = 'US';
            data.stripePublishableKey = 'pk_test_PInFiPUnGR6pzLYZ2IE6oyPf';
        }else{
            data.country = country;
            data.currency = currency;
            data.stripeCountry = stripeCountry;
            data.stripePublishableKey = stripePublishableKey;
        }
        this.config = new StripeConfig(data);
    }

    getConfig() {
        return this.config;
    }
}