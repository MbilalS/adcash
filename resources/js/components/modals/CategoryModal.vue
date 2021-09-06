<template>
    <modal name="category-modal"
        @before-open="openModal"
        @opened="open"
        @closed="closed"
        :clickToClose="false"
        :pivotY="0.1"
        :adaptive="true"
        :scrollable="true"
        height="auto"
        width="1000"
        id="category-modal"
    >
        <div class="container categoryContainer">
            <div slot="top-right">
                <button @click="closeModal()" class="btn btn-primary btn-danger cross-button">
                    Close
                </button>
            </div>
            <div class="row justify-content-center mt-5">
                <h3 v-if="editMode" class="p-3 text-center">Update Category</h3>
                <h3 v-else class="p-3 text-center">Create a new Category</h3>
            </div>
            <div class="row justify-content-center mt-2">
                <div class="col-sm col-md col-lg">
                    <div class="category-name">
                        <input required type="text" class="form-control" placeholder="Name" v-model="categoryData.name" :maxlength="nameMaxSize">
                        <div> {{nameMaxSize - categoryData.name.length}}</div>
                    </div>
                    <span v-if="errors.name" class="error">This field is required.</span>
                    <span v-if="errors.exp" class="error"> Category must contain only letters, numbers, underscores and no spaces.</span>
                </div>
            </div>
            <div class="row justify-content-center mt-5 new-category-button">
                <button v-if="editMode" @click="updateCategories" type="button" class="btn btn-primary" :disabled="is_processing">Update Category</button>
                <button v-else @click="newCategory" type="button" class="btn btn-primary" :disabled="is_processing">Add Category</button>
            </div>
            <div class="alert alert-success mt-5" v-if="is_success">
                <p class="mt-3">Category has been created successfully</p>
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
        name: "category-modal",
        components: {
        },
        data() {
            return {
                config: {
                    headers: {
                        Accept: "application/json",
                    },
                },
                categoryData: {
                    name: ''
                },
                is_success: false,
                is_processing: false,
                errors: {
                    name: false,
                    exp: false
                },
                nameMaxSize: 15,
                editMode: false,
                apiError: '',
            }
        },
        mounted() {
        },
        methods: {
            ...mapActions({
                addCategory: 'addCategory',
                updateCategory: 'updateCategory'
            }),
            openModal(event) {
                if (event.params.edit === true) {
                    this.editMode = event.params.edit;
                    this.categoryData = event.params.data.category;
                }
            },
            checkValidation: function () {
                if (!this.categoryData.name) {
                    this.errors.name = true;
                } else {
                    this.errors.name = false;
                }
                if(/^[\w]+$/.test(this.categoryData.name)) {
                    this.errors.exp = false;
                } else {
                    this.errors.exp = true;
                }
            },
            newCategory() {
                this.checkValidation();
                if(!this.errors.name && !this.errors.exp) {
                    this.is_processing = true;
                    axios.post(`http://localhost:3000/api/categories/store`, this.categoryData)
                        .then(response => {
                            this.addCategory(response.data);
                            this.is_success = true;
                            this.categoryData.name = '';
                        })
                        .catch(error => {
                            this.apiError = error.response.data.message;
                        });
                        this.addAlertDelay();
                }
            },
            updateCategories() {
                this.checkValidation();
                if(!this.errors.name && !this.errors.exp) {
                    this.is_processing = true;
                    axios.put(`http://localhost:3000/api/categories/update`, this.categoryData)
                        .then(response => {
                            this.updateCategory(response.data);
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
            open() {
            },
            closeModal() {
                this.$modal.hide('category-modal');
            },
            closed() {
                this.categoryData = {
                    name: ''
                },
                this.is_success = false;
                this.is_processing = false;
                this.errors = {
                    name: false,
                };
                this.editMode = false;
            },
        }
    }
</script>

<style scoped lang="scss">
    .categoryContainer {
        min-height: 250px;
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

    .category-name {
        display: flex;
        background: lightgray;

        div {
            width: 10%;
            text-align: center;
            padding: 7px;
        }
    }

    .new-category-button {
        display: flex !important;
    }
</style>