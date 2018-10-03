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
                        <i class="fa fa-times" v-show="isDirty" @click="reset"></i>
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
        extends: VueTypeahead,

        props: ['url', 'property', 'placeholder', 'minLetters', 'maxItems'],

        data() {
            return {
                src: this.url,
                queryParamName: this.property,
                minChars: this.minLetters,
                limit: this.maxItems,
                selectFirst: false,
            };
        },

        watch: {
            items() {
                if (this.current < 0) {
                    this.$emit('select', null);
                }
            },
        },

        methods: {
            debouncedUpdate: _debounce(function() {
                this.update();
            }, 500),
            
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
            
            onBlur() {
                this.items = [];
            },
            
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
