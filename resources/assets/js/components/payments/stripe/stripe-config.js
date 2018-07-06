export default class StripeConfig{
    constructor({country, currency, stripeCountry, stripePublishableKey}) {
        this.country = country;
        this.currency = currency;
        this.stripeCountry = stripeCountry;
        this.stripePublishableKey = stripePublishableKey;
    }
}