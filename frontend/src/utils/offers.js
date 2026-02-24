export const isOfferValid = (product) => {
    if (!product || !product.is_offer_active) return false;

    const now = new Date();

    if (product.offer_start_date) {
        const startDateStr = product.offer_start_date.endsWith('Z') ? product.offer_start_date.slice(0, -1) : product.offer_start_date;
        const startDate = new Date(startDateStr);
        if (startDate > now) return false;
    }

    if (product.offer_end_date) {
        const endDateStr = product.offer_end_date.endsWith('Z') ? product.offer_end_date.slice(0, -1) : product.offer_end_date;
        const endDate = new Date(endDateStr);
        if (endDate < now) return false;
    }

    return true;
};

export const getEffectivePrice = (product) => {
    if (isOfferValid(product) && product.discount_price !== null) {
        return Number(product.discount_price);
    }
    return Number(product.price);
};

export const getDiscountPercentage = (product) => {
    if (isOfferValid(product)) {
        if (product.discount_percentage) {
            return product.discount_percentage;
        } else if (product.discount_price !== null && product.price > 0) {
            const savings = product.price - product.discount_price;
            return Math.round((savings / product.price) * 100);
        }
    }
    return 0;
};

export const isExpiringSoon = (product) => {
    if (!isOfferValid(product) || !product.offer_end_date) return false;

    const now = new Date();
    const endDateStr = product.offer_end_date.endsWith('Z') ? product.offer_end_date.slice(0, -1) : product.offer_end_date;
    const endDate = new Date(endDateStr);
    const diffHours = (endDate - now) / (1000 * 60 * 60);

    // Return true if expiring in less than 24 hours
    return diffHours > 0 && diffHours <= 24;
};
