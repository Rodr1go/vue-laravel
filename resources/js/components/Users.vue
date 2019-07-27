<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Usuários</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Adicionar Novo
                        <i class="fas fa-user-plus fa-fw"></i>
                    </button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Registrado em</th>
                    <th>Ações</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | myDate}}</td>
                    <td>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        | 
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red"></i> 
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        
        <!-- Exibe página 404 caso o usuário tente acessar 
        a url sem possuir privilégio de acesso -->
        <div v-if="!$gate.isAdminOrAuthor()">
            <not-found></not-found>
        </div> 

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Adicionar Novo</h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">Atualizar Usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateUser() : createUser()">
                  <div class="modal-body">
                      <div class="form-group">
                        <input v-model="form.name" type="text" name="name"
                            placeholder="Nome"
                            class="form-control" :class="{ 'is-invalid':
                            form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                      </div>

                      <div class="form-group">
                        <input v-model="form.email" type="email" name="email"
                            placeholder="Email"
                            class="form-control" :class="{ 'is-invalid':
                            form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                      </div>

                      <div class="form-group">
                        <textarea v-model="form.bio" name="bio" id="bio"
                            placeholder="Breve descrição do usuário (Opcional)"
                            class="form-control" :class="{ 'is-invalid':
                            form.errors.has('bio') }"></textarea>
                        <has-error :form="form" field="bio"></has-error>
                      </div>

                      <div class="form-group">
                        <select name="type" v-model="form.type" id="type" class="form-control" :class="{
                          'is-invalid': form.errors.has('type') }">
                          <option value="" disabled selected>-- Selecione o tipo de permissão --</option>
                          <option value="admin">Administrador</option>
                          <option value="user">Usuário comum</option>
                          <option value="autor">Autor</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                      </div>

                      <div class="form-group">
                        <input v-model="form.password" type="password" name="password" id="password"
                            class="form-control" :class="{ 'is-invalid':
                            form.errors.has('password') }">
                        <has-error :form="form" field="password"></has-error>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                      <button v-show="editmode" type="submit" class="btn btn-success">Atualizar</button>
                      <button v-show="!editmode" type="submit" class="btn btn-primary">Salvar</button>
                  </div>
                
                </form>
              </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
  data() {
    return {
      editmode: false,
      users: {},
      form: new Form({
        id: '',
        name: '',
        email: '',
        password: '',
        type: '',
        bio: '',
        photo: ''
      })
    }
  },
  methods: {
    // Método para obter os resultados de um endpoint Laravel
		getResults(page = 1) {
			axios.get('api/user?page=' + page)
				.then(response => {
				this.users = response.data;
			});
		},
    updateUser() {
      this.$Progress.start();
      //console.log('editing data')
      this.form.put('api/user/'+this.form.id)
      .then(() => {
        // success
        $('#addNew').modal('hide');
        Swal.fire(
          'Atualizado com sucesso!',
          'As informações do usuário foram atualizadas.',
          'success'
        )
        this.$Progress.finish();
        Fire.$emit('AfterCreate');
      })
      .catch(() => {
        this.$Progress.fail();
      });
    },
    editModal(user) {
      this.editmode = true;
      //this.form.clear();
      this.form.reset();
      $('#addNew').modal('show');
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.clear();
      this.form.reset();
      $('#addNew').modal('show');
    },
    deleteUser(id) {
      Swal.fire({
        title: 'Você tem certeza?',
        text: "Você não poderá reverter isso!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, exclua!'
      }).then((result) => {

        // Envia a requisição p/ o servidor
        if(result.value) {
          this.form.delete('api/user/'+id).then(() => {
            Swal.fire(
            'Excluído!',
            'O usuário foi deletado.',
            'success'
            )
            Fire.$emit('AfterCreate');
          }).catch(() => {
            Swal.fire('Falhou!', 'Algo deu errado!',
            'warning');
          });
        }
      });
    },
    loadUsers() {
      if(this.$gate.isAdminOrAuthor()) {
        axios.get('api/user').then(({ data }) => (this.users = data));
      }
    },
    createUser() {
      this.$Progress.start();
      this.form.post('api/user')
      .then(() => {
        Fire.$emit('AfterCreate');
        $('#addNew').modal('hide')
  
        Toast.fire({
          type: 'success',
          title: 'Usuário cadastrado com sucesso!'
        });
  
        this.$Progress.finish();
      })
      .catch(() => {
        this.$Progress.fail(); 
      })
    }
  }, 
  created() {
    Fire.$on('searching', () => {
      let query = this.$parent.search;
      axios.get('api/findUser?q=' + query)
      .then((data) => {
        this.users = data.data;
      })
      .catch(() => {

      })
    }),
    this.loadUsers(); 
    Fire.$on('AfterCreate', () => {
      this.loadUsers();
    });
    //setInterval(() => this.loadUsers(), 3000);
  }
}
</script>

