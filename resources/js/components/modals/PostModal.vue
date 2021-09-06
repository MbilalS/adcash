<template>
    <modal name="post-modal"
        @before-open="openModal"
        @opened="open"
        @closed="closed"
        :clickToClose="false"
        :pivotY="0.1"
        :adaptive="true"
        :scrollable="true"
        height="auto"
        width="1000"
        id="post-modal"
    >
        <div class="container postContainer">
            <div slot="top-right">
                <button @click="closeModal()" class="btn btn-primary btn-danger cross-button">
                    Close
                </button>
            </div>
            <div class="row justify-content-center mt-5">
                <h3 v-if="editMode" class="p-3 text-center">Update Post</h3>
                <h3 v-else class="p-3 text-center">Create a new Post</h3>
            </div>
            <div class="row justify-content-center mt-2">
                <div class="post-title">
                    <input required type="text" class="form-control" placeholder="Title" v-model="postData.title" :maxlength="titleMaxSize">
                    <div> {{titleMaxSize - postData.title.length}}</div>
                </div>
                <span v-if="errors.title" class="error">This field is required.</span>
            </div>
            <div class="row justify-content-center mt-2">
                <select class="form-control" v-model="allCategories.id">
                    <option value="" selected disabled>Choose Category</option>
                    <option v-for="category in allCategories" :key="category.id" :value="category.id">{{ category.name }}</option>
                </select>
            </div>
            <div class="row justify-content-center mt-5 new-post-button">
                <button v-if="editMode" @click="updatePosts" type="button" class="btn btn-primary" :disabled="is_processing">Update Post</button>
                <button v-else @click="newPost" type="button" class="btn btn-primary" :disabled="is_processing">Add Post</button>
            </div>
            <div class="alert alert-success mt-5" v-if="is_success">
                <p class="mt-3">Post has been created successfully</p>
            </div>
            <div class="alert alert-danger mt-5" v-if="apiError">
                <p class="mt-3">{{apiError}}</p>
            </div>
        </div>
    </modal>
</template>

<script>

    import axios from 'axios'
    import { mapActions } from 'vuex';

    export default {
        name: "post-modal",
        components: {
        },
        data(){
            return {
                config: {
                    headers: {
                        Accept: "application/json",
                    },
                },
                postData: {
                    title: '',
                    category: ''
                },
                allCategories: '',
                is_success: false,
                is_processing: false,
                errors: {
                    title: false,
                },
                titleMaxSize: 140,
                editMode: false,
                apiError: '',
            }
        },
        mounted() {
        },
        methods: {
            ...mapActions({
                addPost: 'addPost',
                updatePost: 'updatePost'
            }),
            openModal(event) {
                if (event.params.edit === true) {
                    this.editMode = event.params.edit;
                    this.postData = event.params.data.post;
                    console.log(this.postData, 'this.postData');
                }

                axios.get(`http://localhost:3000/api/categories`)
                    .then(response => {
                        this.allCategories = response.data;
                        // console.log(this.allCategories[0], 'get all posts.');
                    });
            },
            checkValidation: function () {
                if (!this.postData.title) {
                    this.errors.title = true;
                } else {
                    this.errors.title = false;
                }
            },
            newPost() {
                this.checkValidation();
                if(!this.errors.title) {
                    this.is_processing = true;
                    this.getSelectedCategory();

                    axios.post(`http://localhost:3000/api/posts/store`, this.postData)
                        .then(response => {
                            this.addPost(response.data);
                            this.is_success = true;
                        })
                        .catch(error => {
                            this.apiError = error.response.data.message;
                        });
                        this.addAlertDelay();
                }
            },
            updatePosts() {
                this.checkValidation();
                if(!this.errors.title) {
                    this.is_processing = true;
                    this.getSelectedCategory();

                    axios.put(`http://localhost:3000/api/posts/update`, this.postData)
                        .then(response => {
                            this.updatePost(response.data);
                            this.is_success = true;
                        })
                        .catch(error => {
                            this.apiError = error.response.data.message;
                        });
                        this.addAlertDelay();
                }
            },
            addAlertDelay() {
                setTimeout(() => {  this.is_success = false; this.is_processing = false; this.apiError = ''; }, 2000);
            },
            getSelectedCategory() {
                this.postData.category = this.allCategories.filter((category, index) => {return category.id == this.allCategories.id});
            },
            open() {
            },
            closeModal() {
                this.$modal.hide('post-modal');
            },
            closed() {
                this.postData = {
                    title: '',
                    categories: null
                };
                this.is_success = false;
            },
        }
    }
</script>
 

<style scoped lang="scss">
    .postContainer {
        min-height: 300px;
        width: 60%;

        .row {
            display: block;
        }
    }

    .cross-button {
        position: absolute;
        left: 0;
        top: 0;
    }

    input, select {
        width: 100%;
    }

    .error {
        color: #cc0000;
    }

    .post-title {
        display: flex;
        background: lightgray;

        div {
            width: 10%;
            text-align: center;
            padding: 7px;
        }
    }

    .new-post-button {
        display: flex !important;
    }
</style>