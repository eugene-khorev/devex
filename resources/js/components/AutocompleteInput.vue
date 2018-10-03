<template>
    <div>
        <div class="input-group">
            <input type="text" class="form-control"
                   :placeholder="placeholder"
                   autocomplete="off"
                   v-model="query"
                   @keydown.down="down"
                   @keydown.up="up"
                   @keydown.enter="hit"
                   @keydown.esc="reset"
                   @blur="onBlur"
                   @input="debouncedUpdate"/>
            
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fa fa-spinner fa-spin" v-if="loading"></i>

                    <template v-else>
                        <i class="fa fa-search" v-show="isEmpty"></i>
                        <i class="fa fa-times" v-show="isDirty" @click="onReset"></i>
                    </template>
                </span>
            </div>
            
            <div class="suggestions">
                <ul v-show="hasItems" class="nav nav-pills flex-column">
                    <li v-for="(item, $item) in items" class="nav-item" @mousedown="hit" @mousemove="setActive($item)">
                        <span v-bind:class="[ 'nav-link', activeClass($item) ]" v-text="item.address"></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import _debounce from 'lodash/debounce';
    import VueTypeahead from 'vue-typeahead';

    export default {
        extends: VueTypeahead, // Helper mixin

        props: ['url', 'property', 'placeholder', 'minLetters', 'maxItems'],

        data() {
            return {
                src: this.url,  // // API autocomplete url
                queryParamName: this.property, // API request GET parameter name
                minChars: this.minLetters, // Minimum number of letters to run API request
                limit: this.maxItems, // Maximum items to display
                selectFirst: false, // Disable auto selection of 1st address
            };
        },

        watch: {
            // Watches 'item' property to reset selection
            items() {
                if (this.current < 0) {
                    this.$emit('select', null);
                }
            },
        },

        methods: {
            // Debounced API request function
            debouncedUpdate: _debounce(function() {
                this.update();
            }, 500),
            
            // Check response error
            prepareResponseData(data) {
                if (data && data.items) {
                    return data.items;
                } else {
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        console.error('Неизвестная ошибка');
                    }
                }
            },
            
            // Removes drop-down address list
            onBlur() {
                this.items = [];
            },
            
            // Resets input field
            onReset() {
                this.reset();
                this.current = -1;
                this.$emit('select', null);
            },
            
            // Adress selection event handler
            onHit(item) {
                this.query = item.address;
                this.$emit('select', item);
                this.items = [];
            },
        },
    }
</script>

<style>
    .suggestions {
        position: absolute;
        width: 100%;
    }
    .suggestions .nav {
        margin: 36px 36px 0 0;
        background: rgba(0, 0, 0, 0.03);
    }
</style>
