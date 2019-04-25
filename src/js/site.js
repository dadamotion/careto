require('./bootstrap');

import gdpr from './components/gdpr';
import axios from 'axios';
const Vue = require('vue');
window.Vue = Vue;

new Vue({
    el: '#site',

    components: {
        gdpr,
    },

    mounted() {
        this.getVideos('.e-video--cover');
    },

    data: {
        menuOpen: false,
        players: [],
        player: null,
        options: {
            autoplay: true,
            controls: [],
        },     
        supportsLocalStorage: true,
        selectedDropdown: null,
        dropdown: null,
        mailSuccess: false,
        mailError: false,
    },

    methods: {

        getVideos(className) {
            const Plyr = require('plyr');
            this.players = Array.from(document.querySelectorAll(className)).map(p => new Plyr(p, this.options));
        },
        
        submitHandler() {
            this.postForm();
        },

        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },

        toggleDropdown(id) {
            this.selectedDropdown = id || null;
            this.dropdown = id || null;
        },

        showDropdown(id) {
            if (!this.selectedDropdown) {
                return false;
            }

            return this.selectedDropdown === id;
        },

        resetDropdown() {
            this.selectedDropdown = null;
        },

        postForm() {
            this.content = new FormData(this.$refs.contact);

            axios.post(window.location.origin, this.content)
                .then((success) => {
                    this.mailSuccess = true;
                })
                .catch((error) => {
                    // eslint-disable-next-line no-console
                    console.error(error);
                });
        },

        // Pre-render pages when the user mouses over a link
        // Usage: <a href="" @mouseover="prerenderLink">
        prerenderLink(e) {
            var head = document.getElementsByTagName('head')[0];
            var refs = head.childNodes;
            var ref = refs[ refs.length - 1];
            var elements = head.getElementsByTagName('link');
            Array.prototype.forEach.call(elements, function (el, i) {
                if (('rel' in el) && (el.rel === 'prerender'))
                    el.parentNode.removeChild(el);
            });
            var prerenderTag = document.createElement('link');
            prerenderTag.rel = 'prerender';
            prerenderTag.href = e.currentTarget.href;
            ref.parentNode.insertBefore(prerenderTag,  ref);
        },

    },

});