<template>
    <div class="mb-4"  :class="[size]" v-if="!inline">
        <label 
            :for="id"
            v-if="label"
            :class="[...labelClasses]">{{ label }}</label>
        <input
            :type="type"
            :id="id"
            :name="name"
            :step="step"
            :min="min"
            class="border-2 border-slate-300 rounded-lg py-2 px-4 block w-full text-gray-900 text-sm focus:outline-none focus:border-blue-200"
            :class="[...inputClasses]"
            :value="modelValue"
            @input="updateModelValue"
            @blur="blur"
            @focus=""
            :disabled="disabled"
            :required="required"/>
        <p v-for="error in errors" class="text-red-500 font-bold text-md">{{ error }}</p>
    </div>
    <template v-else>
        <label 
            :for="id"
            v-if="label"
            :class="[...labelClasses]">{{ label }}</label>
        <input
            :type="type"
            :id="id"
            :name="name"
            :step="step"
            :min="min"
            class="border-2 border-slate-300 rounded-lg py-2 px-4 text-gray-900 text-sm focus:outline-none focus:border-blue-200"
            :class="[...inputClasses]"
            :value="modelValue"
            @input="updateModelValue"
            @blur="blur"
            @focus=""
            :disabled="disabled"
            :required="required"/>
        <p v-for="error in errors" class="text-red-500 font-bold text-md">{{ error }}</p>
    </template>
</template>
<script>
    export default {
        props: {
            modelValue: {
                required: true,
            },

            type: {
                type: String,
                default: 'text',
            },

            id: {
                type: String,
                default: null
            },

            label: {
                type: String
            },

            name: {
                type: String,
                default: null
            },

            inline: {
                type: Boolean,
            },

            step: {
                type: Number,
                default: null
            },

            size: {
                type: String,
                default: '',
            },

            min: {
                type: Number,
                default: null
            },

            disabled: {
                type: Boolean,
            },

            required: {
                type: Boolean,
            },

            validate: {
                type: Array,
                default: [],
            },

            errors: {
                type: Array,
                default: [],
            },

            inputClasses: {
                type: Array,
                default: [],
            },

            labelClasses: {
                type: Array,
                default: [],
            }
        },

        methods: {
            updateModelValue () {
                this.$emit('update:modelValue', event.target.value);
                this.$emit('custom');
            },

            blur () {
                const value = event.target.value;
               
                this.validate.forEach(validation => {
                    Object.keys(validation).forEach(key => {
                        if (key !== 'message') {
                            switch(key) {
                                case 'required':
                                    if (value.length === 0 && !this.errors.includes(validation.message)) {
                                        this.errors.push(validation.message);
                                    } else if (this.errors.includes(validation.message)) {
                                        const index = this.errors.indexOf(validation.message);
                                        if (index !== -1) {
                                            this.errors.splice(index, 1);
                                        }
                                    }
                                break;
                                case 'min':
                                    if (parseInt(value) < parseInt(validation[key]) && !this.errors.includes(validation.message)) {
                                        this.errors.push(validation.message);
                                    } else if (parseInt(value) >= parseInt(validation[key]) && this.errors.includes(validation.message)) {
                                        const index = this.errors.indexOf(validation.message);
                                        if (index !== -1) {
                                            this.errors.splice(index, 1);
                                        }
                                    }
                                break;
                            }
                        }
                    });
                });
                this.$emit('onBlur', value);
            }
        },

        data () {
            return {

            }
        }
    };
</script>