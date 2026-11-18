import Datepicker from "flowbite-datepicker/Datepicker";

Datepicker.locales.pt = {
    days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
    daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
    daysMin: ["D", "S", "T", "Q", "Q", "S", "S"],
    months: [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro",
    ],
    monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
    today: "Hoje",
    clear: "Limpar",
    titleFormat: "MM y",
    format: "dd/mm/yyyy",
    weekStart: 0,
};

/** flowbite-datepicker wrapper; Portuguese locale is always registered. */
export const vDatepicker = {
    mounted(el, { value }) {
        new Datepicker(el, { autohide: true, language: "pt", ...value });
        el.addEventListener("changeDate", () => el.dispatchEvent(new Event("input")));
    },
    unmounted(el) {
        el.datepicker?.destroy();
    },
};
