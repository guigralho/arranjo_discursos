const WEEKDAYS = ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"];
const WEEKDAYS_SHORT = ["dom", "seg", "ter", "qua", "qui", "sex", "sáb"];
const MONTHS = [
    "janeiro", "fevereiro", "março", "abril", "maio", "junho",
    "julho", "agosto", "setembro", "outubro", "novembro", "dezembro",
];
const MONTHS_SHORT = ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"];

/** Parses "dd/mm/yyyy" (optionally with a time) or ISO "yyyy-mm-dd" into a local Date. */
export const parseDate = (value) => {
    if (!value) {
        return null;
    }
    if (value instanceof Date) {
        return value;
    }
    const text = String(value);
    let match = text.match(/^(\d{2})\/(\d{2})\/(\d{4})/);
    if (match) {
        return new Date(Number(match[3]), Number(match[2]) - 1, Number(match[1]));
    }
    match = text.match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (match) {
        return new Date(Number(match[1]), Number(match[2]) - 1, Number(match[3]));
    }
    return null;
};

/** "dd/mm/yyyy" → "yyyy-mm-dd" for native date inputs. */
export const toIsoDate = (value) => {
    const date = parseDate(value);
    if (!date) {
        return "";
    }
    const pad = (n) => String(n).padStart(2, "0");
    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
};

/** "yyyy-mm-dd" → "dd/mm/yyyy" (the format the backend expects). */
export const fromIsoDate = (value) => {
    const match = String(value ?? "").match(/^(\d{4})-(\d{2})-(\d{2})$/);
    return match ? `${match[3]}/${match[2]}/${match[1]}` : "";
};

export const weekdayName = (date) => (date ? WEEKDAYS[date.getDay()] : "");
export const weekdayShort = (date) => (date ? WEEKDAYS_SHORT[date.getDay()] : "");
export const monthName = (index) => MONTHS[index] ?? "";
export const monthShort = (index) => MONTHS_SHORT[index] ?? "";

/** "11/2026" or "01/11/2026" → "novembro de 2026". */
export const monthLabel = (value) => {
    const text = String(value ?? "");
    const match = text.match(/(\d{2})\/(\d{4})$/);
    if (!match) {
        return text;
    }
    return `${MONTHS[Number(match[1]) - 1]} de ${match[2]}`;
};

export const capitalize = (text) => (text ? text.charAt(0).toUpperCase() + text.slice(1) : "");

export const isSameDay = (a, b) =>
    a && b && a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth() && a.getDate() === b.getDate();

export const startOfToday = () => {
    const now = new Date();
    return new Date(now.getFullYear(), now.getMonth(), now.getDate());
};

/** Any supported date string → "dd/mm/yyyy". */
export const formatDate = (value) => {
    const date = parseDate(value);
    if (!date) {
        return "";
    }
    const pad = (n) => String(n).padStart(2, "0");
    return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}`;
};
