import Datepicker from "flowbite-datepicker/Datepicker";

export const vDatepicker = {
    mounted(el, { value }) {
        new Datepicker(el, { autohide: true, ...value });
        el.addEventListener("changeDate", () =>
            el.dispatchEvent(new Event("input"))
        );
    },
    unmounted(el) {
        el.datepicker?.destroy();
    },
};
