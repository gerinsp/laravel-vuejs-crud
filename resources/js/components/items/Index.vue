<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-success" v-if="message" id="alert" role="alert">
                    {{ message }}
                </div>
                <div v-if="showCreate || showEdit">
                    <create :datas="datas" v-if="showCreate" @cancelCreate="cancelCreate" @create="create"></create>
                    <edit v-if="showEdit" :datas="datas" :selectedData="selectedData" @cancelEdit="cancelEdit" @getAllData="getAllData" @updated="updated"></edit>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        Item List
                            <button class="btn btn-sm btn-primary" @click="showFormCreate">Add Items</button>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in datas" :key="index">
                                    <th scope="row">{{ index + 1 }}</th>
                                    <td>
                                       <img :src="'storage/' + data.image" class="image">
                                    </td>
                                    <td>{{ data.name }}</td>
                                    <td>{{ data.type.name }}</td>
                                    <td>
                                        <ul class="list-inline text-start">
                                            <li class="list-inline-item">
                                                <button class="btn btn-sm btn-warning"  @click="startEdit(data)">Edit</button>
                                            </li>
                                            <li class="list-inline-item">
                                                <form action="#" @submit.prevent="deleteData">
                                                    <input type="hidden" name="id" :value="data.id">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav aria-label="...">
                            <ul class="pagination">
                                <li class="page-item disabled">
                                <a class="page-link">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active" aria-current="page">
                                <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import Post from './Create.vue';
    import Edit from './Edit.vue';

    export default {
        data() {
            return {
                showCreate: false,
                showEdit: false,
                datas: [],
                selectedData: null,
                message: null,
                postImage: ''
            };
        },
        mounted() {
            console.log('Component mounted.')
            this.getAllData()
        },
        components: {
            Post,
            Edit
        },
        methods: {
            showFormCreate() {
                this.cancelEdit();
                this.showCreate = true;
            },
            getAllData() {
                axios.get('/api/items')
                .then(response => {
                    console.log(response.data)
                    this.datas = response.data
                })
                .catch(err => {
                    console.error(err)
                });
            },
            startEdit(data) {
                this.selectedData = data;
                this.showEdit = true;
                this.cancelCreate();
            },
            cancelEdit() {
                this.selectedData = null;
                this.showEdit = false;
            },
            deleteData() {
                const id = event.target.elements.id.value;
                axios.delete(`/api/items/${id}`)
                .then(response => {
                    this.getAllData();
                    this.message = response.data.message;
                    setTimeout(() => {
                        this.message = null;
                    }, 3000);
                })
                .catch(err => {
                    console.error(err)
                });
            },
            updated(data) {
                console.log(data.message)
                this.message = data.message;
                setTimeout(() => {
                    this.message = null;
                }, 3000);
            },
            cancelCreate() {
                this.showCreate = false;
            },
            create(data) {
                console.log(data.message)
                this.message = data.message;
                setTimeout(() => {
                    this.message = null;
                }, 3000);
            }
        }
    }
</script>

<style scoped>
 .image {
    width:100px;
 }
</style>
