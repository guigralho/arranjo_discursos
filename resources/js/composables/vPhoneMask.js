/**
 * Brazilian phone mask: (##) ####-#### or (##) #####-####.
 * Dispatches an `input` event after formatting so v-model receives the masked value.
 */
export const formatPhone = (value) => {
    const digits = String(value ?? "").replace(/\D/g, "").slice(0, 11);
    if (digits.length === 0) {
        return "";
    }
    if (digits.length <= 2) {
        return `(${digits}`;
    }
    const area = digits.slice(0, 2);
    const rest = digits.slice(2);
    if (rest.length <= 4) {
        return `(${area}) ${rest}`;
    }
    const splitAt = digits.length === 11 ? 5 : 4;
    return `(${area}) ${rest.slice(0, splitAt)}-${rest.slice(splitAt)}`;
};

export const vPhoneMask = {
    mounted(el) {
        el.addEventListener("input", (event) => {
            if (event.isPhoneMaskEvent) {
                return;
            }
            const formatted = formatPhone(el.value);
            if (formatted !== el.value) {
                el.value = formatted;
                const syntheticEvent = new Event("input", { bubbles: true });
                syntheticEvent.isPhoneMaskEvent = true;
                el.dispatchEvent(syntheticEvent);
            }
        });
    },
};
