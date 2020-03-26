<template>
    <div id="customer">
        
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="cart-title">Customers</h3>
                            <div class="card-tools" style="position: absolute; top: 1rem; right: 0.5rem">
                                <button @click="create" class="btn btn-primary">Add <i class="fas fa-plus"></i></button>
                                <button @click="reload" class="btn btn-primary">Reload <i class="fas fa-sync"></i></button>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-2">
                                        <strong>Search By</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <select v-model="queryField" name="" id="" class="form-control">
                                            <option value="name">Name</option>
                                            <option value="email">Email</option>
                                            <option value="phone">Phone</option>
                                            <option value="address">Address</option>
                                            <option value="total">Total</option>
                                        </select>
                                    </div>
                                    <div class="col-md-7">
                                        <input type="text" class="form-control" v-model="query">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">

                                    <thead>
                                        <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Total</th>
                                        <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-show="customers.length" v-for="(customer,index) in customers" :key="customer.id">
                                        <th scope="row">{{ index +1 }}</th>
                                        <td>{{customer.name}}</td>
                                        <td>{{customer.email}}</td>
                                        <td>{{customer.phone}}</td>
                                        <td>{{customer.total}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info">
                                                View
                                            </button>
                                            <button @click="edit(customer)" class="btn btn-sm btn-primary">
                                                Edit
                                            </button>
                                            <button @click="destroy(customer)" class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </td>
                                        </tr>
                                        <tr v-show="!customers.length">
                                            <td colspan="6">
                                                <div class="alert alert-danger" role="alert">
                                                    Soorry :) No data found
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                            <paginate v-if="pagination.last_page > 1" :pagination="pagination" :offset="5" @paginate="query === '' ? getCustomers() : searchCustomers()"></paginate>
                        </div>
                    </div>
                </div>
            </div>
            <!----Add Customer Modal --->
            <div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Customer {{editMode ? 'Edit' : 'Add' }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? uddate() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input v-model="form.name" type="text" name="name"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="form.email" type="text" name="email"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input v-model="form.phone" type="text" name="phone"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea v-model="form.address" type="text" name="address"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                                <has-error :form="form" field="address"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Total</label>
                                <input v-model="form.total" type="text" name="total"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('total') }">
                                <has-error :form="form" field="total"></has-error>
                            </div>
                        
                  
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button :disabled="form.busy" type="submit" class="btn btn-primary">{{editMode ? 'Update' : 'Save' }}</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
        <vue-progress-bar></vue-progress-bar>
        <vue-snotify></vue-snotify>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
            this.getCustomers()
        },
        data(){
            return {
                editMode: false,
                customers: [],
                pagination: {
                    current_page: 1
                },
                queryField: 'name',
                query: '',
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    total: '',
                })
            }
        },

        watch: {
            query(newValue,oldValue){

                if(newValue === ''){
                    this.getCustomers();
                }else{
                    this.searchCustomers();
                }

            }
        },

        methods: {
            getCustomers(){
                this.$Progress.start()
                axios.get('/api/customers?page='+this.pagination.current_page)
                .then((response) => {
                    this.customers = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish()
                })
                .catch((e) => {
                    this.$Progress.fail()
                })
            },

            searchCustomers(){
                this.$Progress.start()
                axios.get('/api/search/customers/'+this.queryField+'/'+this.query+'?page='+this.pagination.current_page)
                .then(response => {
                    this.customers = response.data.data;
                    this.pagination = response.data.meta;
                    this.$Progress.finish()
                })
                .catch(e => {
                    this.$Progress.fail()
                })
            },
            reload(){
                this.getCustomers();
                this.query = '';
                this.queryField = 'name';
                this.$snotify.success('Successfully Refresh','Success');
            },
            create(){
                this.editMode = false;
                this.form.clear()
                this.form.reset()
                $('#addCustomerModal').modal('show')
            },
            store(){
                this.$Progress.start();
                this.form.busy = true;

                this.form.post('/api/customers')
                .then(response => {

                    this.getCustomers();
                    $('#addCustomerModal').modal('hide')
                    if(this.form.successful){
                        this.$Progress.finish();
                        this.$snotify.success('Successfully Save Customer','Success');
                    }else{
                        this.$Progress.finish();
                        this.$snotify.error('Something went wrong !!','Success');
                    }

                })
                .catch(e => {
                    this.$Progress.finish();
                })

                
            },
            edit(customer){
                this.editMode = true;
                this.form.clear();
                this.form.reset();
                this.form.fill(customer);
                $('#addCustomerModal').modal('show')
            },

            uddate(){

                this.$Progress.start();
                this.form.busy = true;

                this.form.put('/api/customers/'+this.form.id)
                .then(response => {

                    this.getCustomers();
                    $('#addCustomerModal').modal('hide')
                    if(this.form.successful){
                        this.$Progress.finish();
                        this.$snotify.success('Successfully Updated Customer','Success');
                    }else{
                        this.$Progress.finish();
                        this.$snotify.error('Something went wrong !!','Success');
                    }

                })
                .catch(e => {
                    this.$Progress.finish();
                })
            },

                destroy(customer) {
                this.$snotify.clear();
                this.$snotify.confirm(
                    "You will not be able to recover this data!",
                    "Are you sure?",
                    {
                    showProgressBar: false,
                    closeOnClick: false,
                    pauseOnHover: true,
                    buttons: [
                        {
                        text: "Yes",
                        action: toast => {
                            this.$snotify.remove(toast.id);
                            this.$Progress.start();
                            axios
                            .delete("/api/customers/" + customer.id)
                            .then(response => {
                                this.getCustomers();
                                this.$Progress.finish();
                                this.$snotify.success(
                                "Customer Successfully Deleted",
                                "Success"
                                );
                            })
                            .catch(e => {
                                this.$Progress.fail();
                                console.log(e);
                            });
                        },
                        bold: true
                        },
                        {
                        text: "No",
                        action: toast => {
                            this.$snotify.remove(toast.id);
                        },
                        bold: true
                        }
                    ]
                    }
                );
                }
        }
    }
</script>
