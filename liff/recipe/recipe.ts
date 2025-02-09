
import {createApp} from "vue"
import {vuetify} from"../util/vuetify"
import Recipe from "./Recipe.vue"

(window.onload = () => {

  createApp(Recipe).use(vuetify).mount('#container')

});

