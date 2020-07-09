<template>
    <!-- MODAL CLIENTE -->
    <div class="modal fade" role="dialog" style="overflow-y: scroll;" id="modalCliente" >
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" @click="cerrarModal()">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Cliente</h4>
                </div>

                <div class="modal-body">

                    <form class="form">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label >Tipo Doc. Identidad  </label>
                                        <select  class="form-control" v-model="cliente.tipo_documento">
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                        </select>                    
                                    </div>
                                </div>

                                
                               <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Numero<span style="color:red;" v-show="encontrarCliente.length">    (Cliente ya existe) </span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" v-model="cliente.num_documento" v-show="cliente.tipo_documento=='DNI'"   maxlength="8" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" @keyup.enter="traerNombre()">
                                            <input type="number" class="form-control" v-model="cliente.num_documento" v-show="cliente.tipo_documento=='RUC'"   maxlength="11" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" @keyup.enter="traerNombre()">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" @click="traerNombre()"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                              
                                <div class="col-md-12" v-show="cliente.tipo_documento=='DNI'">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" class="form-control" v-model="cliente.nombre"   @input="cliente.nombre = $event.target.value.toUpperCase()">
                                    </div>
                                </div>

                                <div class="col-md-12" v-show="cliente.tipo_documento=='RUC'">
                                    <div class="form-group">
                                        <label>Razón Social</label>
                                        <input type="text" class="form-control" v-model="cliente.razon_social"  @input="cliente.razon_social = $event.target.value.toUpperCase()">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Correo</label>
                                        <input type="text" class="form-control" v-model="cliente.correo">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" class="form-control" v-model="cliente.telefono">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Dirección</label>
                                        <input type="text" class="form-control" v-model="cliente.direccion" @input="cliente.direccion = $event.target.value.toUpperCase()">
                                    </div>
                                </div>

                                <div v-show="errorCliente" class="form-group row div-error">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <ul>
                                                <li v-for="error in errorMostrarMsjCliente" :key="error" v-text="error"></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
    
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-default pull-left" @click="cerrarModal()">Cerrar</button>
                        <button type="submit" class="btn btn-primary pull-right" style="margin-right:30px;" @click="registrarCliente()">Registrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState,mapActions} from 'vuex'
    export default {
        props:['enlace'],
        data(){
            return {
                cliente: {
                    id:0,
                    tipo_persona:'Cliente',
                    tipo_documento:'DNI',
                    num_documento:'',
                    nombre:'',
                    razon_social:'',
                    correo: '',
                    telefono:'',
                    direccion:'',
                },
                errorMostrarMsjCliente:[],
                errorCliente:0

            }
        },
        computed:{

            filtrarCliente(){   
                let me = this;
                return me.clientes.filter(cliente => {
                    return  cliente.num_documento;
                });
            },

             encontrarCliente(){
                let me = this;
                return  me.filtrarCliente.filter(i => i.num_documento === me.cliente.num_documento);
            },

             ...mapState(['clientes'])


        },

        methods:{
            ...mapActions(['listarCliente']),
            
                
                traerNombre(){
                    let me = this;
                    let url = ''
                    if (me.cliente.tipo_documento == 'DNI') {

                        if (me.cliente.num_documento.length >= 8) {
                            // url = me.enlace+'/consultardni?dni='+me.cliente.num_documento+''
                            // axios.get(url).then((res) =>{
                            //     if (res.data.estado) {
                            //         me.cliente.nombre = res.data.nombres +' ' +res.data.apellidos;
                            //         me.cliente.direccion = res.data.departamento
                            //     }else{
                            //         me.cliente.nombre = ''
                            //         me.cliente.direccion = ''
                            //     }
                            // })
                            me.buscarDni(me.cliente.num_documento);
                        }
                    
                    }else{
                        
                        if (me.cliente.num_documento.length >= 11) {
                            url = me.enlace+'/consultarruc?ruc='+me.cliente.num_documento+''
                            axios.get(url).then((res) =>{

                                if (res.data.success) {
                                    me.cliente.razon_social = res.data.nombre_o_razon_social
                                    me.cliente.direccion = res.data.direccion
                                }else{
                                    me.cliente.razon_social = ''
                                    me.cliente.direccion = ''
                                }
                            })
                        }
            
                    }
                },


                buscarDni(dni){
                    let me = this;
                    var formData = new FormData();
                    formData.append("token", "598bbb5e-100e-4eae-9fb8-d598a5cf7fd2-a7a81134-9d06-44ba-86bc-75c31be3278b");
                    formData.append("dni", dni);

                    var request = new XMLHttpRequest();

                    request.open("POST", "https://api.migoperu.pe/api/v1/dni");
                    request.setRequestHeader("Accept", "application/json");

                    request.send(formData);
                    request.onload = function() {
                        var data = JSON.parse(this.response);
                    
                        me.cliente.nombre = data.nombre;
                        me.cliente.direccion = data.direccion
                
                };
            },
                    

            validarCliente(){
                let me = this;
                me.errorCliente=0;
                me.errorMostrarMsjCliente =[];
                $(".alert-danger").fadeTo(2000, 3000).slideUp(3000, function(){
                    $(".alert-danger").slideUp(3000);
                });
                if (me.encontrarCliente.length) me.errorMostrarMsjCliente.push("El cliente ya existe.");
                if (me.cliente.num_documento.length < 11 && me.cliente.tipo_documento == 'RUC') me.errorMostrarMsjCliente.push("El número debe de tener 11 dígitos.");
                if (!me.cliente.razon_social && me.cliente.tipo_documento == 'RUC') me.errorMostrarMsjCliente.push("El campo Razón Social no debe de estar nulo.");
                if (me.errorMostrarMsjCliente.length) me.errorCliente = 1;
                return me.errorCliente;      
            },

            registrarCliente(){
                let me = this;
                let url = me.enlace+'/persona';

                if (me.validarCliente()) {
                    return
                }

                swal({
                    title: 'Está seguro registrar este cliente?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                                
                    axios.post(url, me.cliente).then((res) =>{
                        $('#modalCliente').modal('hide');
                        me.listarCliente();
                    })

                }})
                
            },

            cerrarModal(){
                $('#modalCliente').modal('hide')
            }
        }

     
    }
</script>

