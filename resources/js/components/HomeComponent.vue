<template>
    <div class="container">
        <div class="row mt-2">
            <div class="col-sm col-md col-lg align-self-start">
                <button @click="createCategory" type="button" class="btn btn-primary btn-lg">Create Category</button>
            </div>
            <div class="col-sm col-md col-lg align-self-end">
                <button @click="createPost" type="button" class="btn btn-primary btn-lg" style="float: right;">Create Post</button>
            </div>
        </div>
        <div class="alert alert-success mt-5" v-if="is_success">
            <p class="mt-3">{{successfullyDeleted}} has been successfully Deleted</p>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-sm col-md col-lg">
                <h1 class="p-3 text-center">Posts</h1>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(post, index) in posts" :key="post.id">
                            <td>{{post.title}}</td>
                            <template v-if="post.category">
                                <template v-if="post.category.length">
                                    <td>{{post.category[0].name}}</td>
                                </template>
                                <template v-else>
                                    <td>{{post.category.name}}</td>
                                </template>
                            </template>
                            <template v-else>
                                <template v-if="post.category_id">
                                    <td>{{getCategoryName(post.category_id)}}</td>
                                </template>
                                <template v-else>
                                    <td>N/A</td>
                                </template>
                            </template>
                            <td>
                                <button @click="editPost(post, index)" type="button" class="btn btn-primary">Edit</button>
                                <button @click="deletePost(post, index)" type="button" class="btn btn-primary">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dispalying all categories -->

        <div class="row justify-content-center mt-5">
            <div class="col-sm col-md col-lg">
                <h1 class="p-3 text-center">Categories</h1>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in categories" :key="category.id">
                            <td>{{category.name}}</td>
                            <td>
                                <button @click="editCategory(category, index)" type="button" class="btn btn-primary">Edit</button>
                                <button @click="deleteCategory(category, index)" type="button" class="btn btn-primary">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <category-modal></category-modal>
        <post-modal></post-modal>
    </div>
</template>

<script>
    import axios from 'axios'
    import CategoryModal from '././modals/CategoryModal';
    import PostModal from '././modals/PostModal';
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "home",
        components: {
            CategoryModal,
            PostModal
        },
        data(){
            return {
                config: {
                    headers: {
                        // Accept: "application/json",
                    }
                },
                is_success: false,
                successfullyDeleted: '',
            }
        },
        mounted() {
            this.getPosts();
            this.getCategories();
        },
        computed: {
            ...mapGetters({
                posts: 'posts',
                categories: 'categories'
            })
        },
        methods: {
            ...mapActions({
                setInitialPosts: 'setInitialPosts',
                setInitialCategories: 'setInitialCategories',
                removePost: 'removePost',
                removeCategory: 'removeCategory'
            }),
            getPosts() {
                axios.get(`http://localhost:3000/api/posts`)
                    .then(response => {
                        this.setInitialPosts(response.data);
                    });
            },
            getCategories() {
                axios.get(`http://localhost:3000/api/categories`)
                    .then(response => {
                        this.setInitialCategories(response.data);
                    });
            },
            getCategoryName(category_id) {
                let category = this.categories.filter((category, index) => {return category.id == category_id});
                if(category[0]) {
                    return category[0].name;
                } else {
                    return 'N/A';
                }
            },
            createCategory() {
                this.$modal.show('category-modal', { data: {}, edit: false });
            },
            createPost() {
                this.$modal.show('post-modal', { data: {}, edit: false });
            },
            editPost(post, index) {
                this.$modal.show('post-modal', { data: {post}, edit: true });
            },
            deletePost(post, index) {
                axios.delete(`http://localhost:3000/api/posts/destroy/${post.id}`)
                    .then(response => {
                        this.is_success = true;
                        this.successfullyDeleted = 'Post',
                        this.removePost(index);
                    });
                    this.addAlertDelay();
            },
            editCategory (category, index) {
                this.$modal.show('category-modal', { data: {category}, edit: true });
            },
            deleteCategory (category, index) {
                axios.delete(`http://localhost:3000/api/categories/destroy/${category.id}`)
                    .then(response => {
                        this.is_success = true;
                        this.successfullyDeleted = 'Category',
                        this.removeCategory(index);
                    });
                    this.addAlertDelay();
                    this.getPosts();
            },
            addAlertDelay() {
                setTimeout(() => {  this.is_success = false; }, 2000);
            }
        }
    }
</script>

<style scoped lang="scss">
    .alert-success {
        position: fixed;
        z-index: 1;
    }
</style>
 