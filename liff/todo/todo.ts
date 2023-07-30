
import {createApp} from "vue"
import {vuetify} from"../util/vuetify"
import Todo from "./Todo.vue"

(window.onload = () => {

  createApp(Todo).use(vuetify).mount('#container')

});

