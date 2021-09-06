import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        posts: null,
        categories: null,
    },
    getters: {
        posts (state) {
            return state.posts
        },
        categories (state) {
            return state.categories
        }
    },
    mutations: {
        setPosts (state, posts) {
            state.posts = posts
        },
        addNewPost (state, post) {
            state.posts.push(post)
        },
        updatePost (state, payload) {
            const index = state.posts.findIndex(post => post.id === payload.id)
            if (index !== -1) state.posts.splice(index, 1, payload)
        },
        removePost (state, postIndex) {
            state.posts.splice(postIndex, 1)
        },

        // Categories
        setCategories (state, categories) {
            state.categories = categories
        },
        addNewCategory (state, category) {
            state.categories.push(category)
        },
        updateCategory (state, payload) {
            const index = state.categories.findIndex(category => category.id === payload.id)
            if (index !== -1) state.categories.splice(index, 1, payload)
        },
        removeCategory (state, categoryIndex) {
            state.categories.splice(categoryIndex, 1)
        }
    },
    actions: {
        setInitialPosts (context, posts) {
            context.commit('setPosts', posts)
        },
        removePost (context, postIndex) {
            context.commit('removePost', postIndex)
        },
        addPost (context, post) {
            context.commit('addNewPost', post)
        },
        updatePost (context, payload) {
            context.commit('updatePost', payload)
        },

        // Categories
        setInitialCategories (context, categories) {
            context.commit('setCategories', categories)
        },
        removeCategory (context, categoryIndex) {
            context.commit('removeCategory', categoryIndex)
        },
        addCategory (context, category) {
            context.commit('addNewCategory', category)
        },
        updateCategory (context, payload) {
            context.commit('updateCategory', payload)
        }
    }
});

export default store;