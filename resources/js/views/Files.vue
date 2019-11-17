<template>
	<div id="offers">
		<div class="vx-row">
			<div class="vx-col w-full">
				<vx-card title="Files">
					<div class="mt-4">

						<div v-show="activePrompt" class="vx-row">
							<div class="vx-col sm:w-1/4 w-full mb-2">
								<vs-input class="w-full" label-placeholder="File Description" v-model="file.description" />
							</div>
							<div class="vx-col sm:w-1/4 w-full mt-4">
								<input type="file" class="custom-file-input" ref="file"  v-on:change="handleFileUpload()" />
								<vs-button color="primary" @click="triggerFile()" type="border" icon-pack="feather" icon="icon-upload">
									<span v-if="file.file">{{file.file.name}}</span>
									<span v-else>Select Files</span>
								</vs-button>
							</div>
							<div class="vx-col sm:w-1/4 w-full mt-4">
								<vs-button @click="addFile" >Add File</vs-button>
							</div>
						</div>

						<div slot="fc-header-right" class="flex justify-end">
							<vs-button v-show="!activePrompt" icon-pack="feather" icon="icon-plus" @click="activePrompt = true" >New File</vs-button>
						</div>
						
						<vs-table :data="files" pagination max-items="10" search>
							<template slot="thead">
								<vs-th sort-key="id">FILE ID.</vs-th>
                                <vs-th sort-key="status">STATUS</vs-th>
								<vs-th sort-key="name">NAME</vs-th>
                                <vs-th>DESCRIPTION</vs-th>
                                <vs-th sort-key="emails">EMAILS</vs-th>
								<vs-th sort-key="created_at">UPLOAD DATE</vs-th>
							</template>

							<template slot-scope="{data}">
								<vs-tr :key="indextr" v-for="(tr, indextr) in data">
									<vs-td>
										<span>#{{tr.id}}</span>
									</vs-td>
                                    <vs-td>
										<span class="flex items-center text-center px-2 py-1 rounded">
											<div v-if="tr.state == 1" class="h-3 w-3 ml-4 rounded-full mr-2" :class="'bg-' + getStatusColor(tr.state)"></div>
											<vs-button radius :disabled="isRefresh" :class="{refresh : isRefresh }" v-else @click="refreshFile(tr.id)" color="warning" type="flat" icon-pack="feather" icon="icon-refresh-cw"></vs-button>
										</span>
									</vs-td>
									<vs-td>
										<span>{{tr.name}}</span>
									</vs-td>
                                    <vs-td>
										<span>{{tr.description}}</span>
									</vs-td>
                                    <vs-td>
										<span>{{tr.emails}}</span>
									</vs-td>
									<vs-td>
										<span>{{tr.created_at}}</span>
									</vs-td>
									<vs-td>
										<vs-button color="danger" type="gradient" @click="deleteFile(tr.id)">Delete</vs-button>
										<vs-button type="gradient" @click="exportData(tr.id)">Export Visitors</vs-button>
									</vs-td>
								</vs-tr>
							</template>
						</vs-table>
					</div>
					
				</vx-card>
			</div>
		</div>
		
	</div>
</template>

<script>
import { setTimeout } from 'timers';
export default{
	data() {
		return {
			activePrompt: false,
			isRefresh: false,
            settings: {
				maxScrollbarLength: 60,
				wheelSpeed: 0.30,
            },
            file: {
                file: '',
                description: '',
            },
		}
    },
    created(){
        if(this.files.length > 0) return;
        else {
			this.$vs.loading();
			this.$store.dispatch('fetchFiles')
			.then(res => {
				this.$vs.loading.close();
			})
		}
    },
    methods: {
        clearFields() {
			this.file.file = '';
			this.file.description = '';
		},
		triggerFile(){
			this.$refs.file.click();
		},
		addFile() {
			if(this.activePrompt == false ) {
				this.activePrompt = true;
				return;
			}
			this.$store.dispatch('uploadFile', this.file);
			this.$vs.notify({title:'Success',text:'File added successfully',color: '#28C76F',position:'top-center'})
			this.activePrompt = false;
		},
		refreshFile(id){
			this.isRefresh = true;
			this.$store.dispatch('refreshFile', id)
			.then((res) => {
				setTimeout(() => {
					this.isRefresh = false;
				}, 2000)
				
			})
		},
        getStatusColor(status){
            if(status == 1){
                return "success";
            }else{
                return "warning";
            }
        },
        handleFileUpload(){
            this.file.file = this.$refs.file.files[0];
        },
        successUpload(){
           this.$vs.notify({
				title:'File Upload',
				text:"Uploaded Successfully",
				color: 'success',
				position: 'top-center'});
		},
		deleteFile(id){
			this.$swal({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
				if (result.value) {
					this.$store.dispatch('deleteFile', id);
					this.$vs.notify({title:'Success',text:'Your file is deleted successfully',color: '#28C76F',position:'top-center'})
				}
			})
		},
		exportData(id){
			this.$store.dispatch('exportVisitors', id);
		}
    },
    computed: {
        files: {
			get () {
				 return this.$store.getters.files;
			},
			set (value) {
				console.log(value);
			}
           
        }
    }
}
</script>

<style scoped>
.refresh {
	transition: all 1s ease-in-out;
	transform: rotate(360deg)
}

.custom-file-input {
  opacity: 0;
  position: absolute;
  z-index: -1;
}

</style>
