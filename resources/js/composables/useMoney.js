/** Brazilian currency helpers (no external dependency). */
export const formatCurrency = (value) =>
    Number(value ?? 0).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });

/** "1.234,56" → "1234.56" (string the backend parses). */
export const toDecimalString = (value) => String(value ?? "").replace(/\./g, "").replace(",", ".");

/** "1.234,56" → 1234.56 */
export const parseMoney = (value) => {
    const number = parseFloat(toDecimalString(value));
    return Number.isFinite(number) ? number : 0;
};

/** Formats typed digits as cents: "12345" → "123,45". */
export const formatMoneyInput = (value) => {
    const digits = String(value ?? "").replace(/\D/g, "").replace(/^0+(?=\d)/, "");
    if (!digits) {
        return "";
    }
    const cents = digits.padStart(3, "0");
    const integer = cents.slice(0, -2).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return `${integer},${cents.slice(-2)}`;
};

export const vMoneyMask = {
    mounted(el) {
        el.addEventListener("input", (event) => {
            if (event.isMoneyMaskEvent) {
                return;
            }
            const formatted = formatMoneyInput(el.value);
            if (formatted !== el.value) {
                el.value = formatted;
                const syntheticEvent = new Event("input", { bubbles: true });
                syntheticEvent.isMoneyMaskEvent = true;
                el.dispatchEvent(syntheticEvent);
            }
        });
    },
};
