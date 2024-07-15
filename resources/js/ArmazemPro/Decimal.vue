<template>

    <input class="rounded-md border border-solid border-black text-gray-700 py-2 px-3"
        :class="[{ 'bg-gray-100': disabled }, isFilled]" v-money="money" :value="modelValue" :readonly="disabled"
        @blur="verifyMaxMin" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>
<script>

export default {
    props: {
        label: {
            type: [String, Boolean],
            default: false
        },
        positive: {
            type: Boolean,
            default: false
        },
        modelValue: {
            type: [Number, String],
            default: null
        },
        precision: {
            type: Number,
            default: 2
        },
        colored: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        isFilled: function () {
            return this.bgColor()
        }
    },
    data() {
        return {
            money: {
                decimal: ',',
                thousands: '.',
                precision: this.precision,
                masked: false
            },
        }
    },
    methods: {
        verifyMaxMin() {
            let val = this.modelValue;
            if ("string" === typeof val) {
                val = parseFloat(val.replace(".", "").replace(",", "."))
            }
            if (this.positive && val < 0) {
                val = val * -1;
                this.$emit("update:modelValue", val.toFixed(2));
                this.$toast.open({
                    message: "O preço não pode ser negativo",
                    type: 'warning',
                    duration: 2500,
                    position: 'top-right'
                });
            }
        },
        focus() {
            this.$refs.input.focus()
        },
        bgColor() {
            let val = this.modelValue;
            if ("string" === typeof this.modelValue) {
                val = parseFloat(this.modelValue.replace(',', '.'));
            }
            return {
                'bg-green-100': this.colored && val > 0,
                'bg-red-100': this.colored && val < 0,
            }
        }
    }
}
</script>