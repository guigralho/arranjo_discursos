<script setup>
import { Menu, MenuButton, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon, EllipsisHorizontalIcon } from "@heroicons/vue/16/solid";

defineProps({
    label: { type: String, default: "Mais ações" },
    /** Show only an ellipsis icon (label stays accessible). */
    iconOnly: { type: Boolean, default: false },
    align: { type: String, default: "right" },
});
</script>

<template>
    <Menu as="div" class="relative inline-block text-left">
        <MenuButton
            :aria-label="iconOnly ? label : undefined"
            :class="iconOnly ? 'w-10 justify-center' : 'gap-1.5 px-3.5'"
            class="inline-flex h-10 items-center rounded-md border border-stone-300 bg-white text-sm font-medium text-stone-800 shadow-sm hover:bg-stone-50 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-100 dark:hover:bg-stone-800"
        >
            <EllipsisHorizontalIcon v-if="iconOnly" aria-hidden="true" class="h-5 w-5" />
            <template v-else>
                {{ label }}
                <ChevronDownIcon aria-hidden="true" class="h-4 w-4 text-stone-500" />
            </template>
        </MenuButton>
        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="scale-95 opacity-0"
            leave-active-class="transition duration-75 ease-in"
            leave-to-class="opacity-0"
        >
            <MenuItems
                :class="align === 'right' ? 'right-0 origin-top-right' : 'left-0 origin-top-left'"
                class="absolute z-40 mt-1.5 w-60 rounded-lg border border-stone-200 bg-white p-1 shadow-float focus:outline-none dark:border-stone-700 dark:bg-stone-900"
            >
                <slot />
            </MenuItems>
        </transition>
    </Menu>
</template>
