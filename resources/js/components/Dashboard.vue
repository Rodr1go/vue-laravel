<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 mt-3">
                
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning" v-if="$gate.isAdminOrAuthor()">
                        <div class="inner">
                            <h3>{{ users }}</h3>

                            <p>Usuários Registrados</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <router-link to="/users" class="small-box-footer">
                          Mais informações <i class="fas fa-arrow-circle-right"></i>
                        </router-link>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
          return { users: 0 }
        },
        methods: {
          countUsers() {
            if(this.$gate.isAdminOrAuthor()) {
              axios.get('api/countUsers').then(({ data }) => (this.users = data));
            }
          },
        },
        created() {
          this.countUsers();
        }, 
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
