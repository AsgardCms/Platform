<template>
    <div class="row" style="margin-top: -35px;">
        <div class="col-xs-12">
            <div class="sc-table">
                 <div class="el-row">
                    <div class="title">
                        <h4 v-if="singleModal">{{ trans('media.choose file') }}</h4>
                        <h3 v-if="! singleModal">{{ trans('media.title.media') }}</h3>
                        <div class="media-breadcrumb">
                            <el-breadcrumb separator="/" v-if="! singleModal">
                                <el-breadcrumb-item>
                                    <a href="/backend">Home</a>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item :to="{name: 'admin.media.media.index'}">{{ trans('media.breadcrumb.media') }}
                                </el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="tool-bar el-row" style="padding-bottom: 20px;">
                            <div class="actions el-col el-col-14">
                                <new-folder :parent-id="folderId"></new-folder>
                                <upload-button :parent-id="folderId"></upload-button>
                                <el-button-group v-if="false">
                                    <el-button type="primary" :disabled="selectedMedia.length === 0">Move</el-button>
                                </el-button-group>
                                <el-button type="danger" :disabled="selectedMedia.length === 0"
                                    @click.preent="batchDelete" :loading="filesAreDeleting">
                                    Delete
                                </el-button>
                            </div>
                            <div class="search el-col el-col-5">
                                <el-input icon="search" @change="performSearch" v-model="searchQuery">
                                </el-input>
                            </div>
                        </div>
                        <el-row>
                            <el-col :span="24">
                                <el-breadcrumb separator="/" style="margin-bottom: 20px;">
                                    <el-breadcrumb-item v-for="(folder, index) in folderBreadcrumb" @click.native="changeRoot(folder.id, index)" :key="folder.id">
                                        {{ folder.name }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>
                            </el-col>
                        </el-row>
                        <el-table
                                :data="data"
                                stripe
                                style="width: 100%"
                                ref="mediaTable"
                                v-loading.body="tableIsLoading"
                                @sort-change="handleSortChange"
                                @selection-change="handleSelectionChange">
                            <el-table-column
                                    type="selection"
                                    width="55">
                            </el-table-column>
                            <el-table-column label="" width="150">
                                <template scope="scope">
                                    <img :src="scope.row.small_thumb" alt="" v-if="scope.row.is_image"/>
                                    <i :class="`fa ${scope.row.fa_icon}`" style="font-size: 38px;"
                                       v-if="! scope.row.is_image && ! scope.row.is_folder"></i>
                                    <i class="fa fa-folder" style="font-size: 38px;"
                                       v-if="scope.row.is_folder"></i>
                                </template>
                            </el-table-column>

                            <el-table-column prop="filename" :label="trans('media.table.filename')" sortable="custom">
                                <template scope="scope">
                                    <strong v-if="scope.row.is_folder" style="cursor: pointer;" @click="enterFolder(scope)">
                                        {{ scope.row.filename }}
                                    </strong>
                                    <span v-else>{{ scope.row.filename }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="created_at" :label="trans('core.table.created at')" sortable="custom"
                                             width="150">
                            </el-table-column>
                            <el-table-column prop="actions" label="" width="150">
                                <template scope="scope">
                                    <div class="pull-right">
                                        <el-button
                                                type="primary"
                                                size="small"
                                                @click.prevent="insertMedia(scope)"
                                                v-if="singleModal && ! scope.row.is_folder">
                                            {{ trans('media.insert') }}
                                        </el-button>
                                        <div v-if="! singleModal">
                                            <el-button-group>
                                                <el-button
                                                        size="small"
                                                        @click.prevent="loadEditForm(scope)"
                                                        v-if="! scope.row.is_folder"><i class="fa fa-pencil"></i></el-button>
                                                    <el-button
                                                            size="small"
                                                            @click.prevent="showEditFolder(scope.row)"
                                                            v-if="scope.row.is_folder && canEditFolders">
                                                        <i class="fa fa-pencil"></i>
                                                    </el-button>
                                                <delete-button :scope="scope" :rows="data"></delete-button>
                                            </el-button-group>
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                            <el-pagination
                                    @size-change="handleSizeChange"
                                    @current-change="handleCurrentChange"
                                    :current-page.sync="meta.current_page"
                                    :page-sizes="[10, 20, 50, 100]"
                                    :page-size="parseInt(meta.per_page)"
                                    layout="total, sizes, prev, pager, next, jumper"
                                    :total="meta.total">
                            </el-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <rename-folder></rename-folder>
    </div>
</template>

<script>
    import axios from 'axios'
    import NewFolder from './NewFolder.vue';
    import UploadButton from './UploadButton.vue';
    import RenameFolder from './RenameFolder.vue';

    export default {
        components: {
            'new-folder': NewFolder,
            'upload-button': UploadButton,
            'rename-folder': RenameFolder,
        },
        props: {
            singleModal: {type: Boolean},
            eventName: {},
        },
        data() {
            return {
                data: [],
                tableIsLoading: false,
                meta: {
                    current_page: 1,
                    per_page: 10,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                folderId: 0,
                selectedMedia: {},
                folderBreadcrumb: [
                    {id: 0, name: 'Home'},
                ],
                filesAreDeleting: false,
                canEditFolders: true,
            }
        },
        methods: {
            queryServer(customProperties) {
                let properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                    folder_id: this.folderId,
                };

                axios.get(route('api.media.all-vue', _.merge(properties, customProperties)))
                    .then(response => {
                        this.tableIsLoading = false;
                        this.data = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;

                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            fetchMediaData() {
                this.tableIsLoading = true;
                if (this.$route.query.folder_id !== undefined) {
                    this.queryServer({folder_id: this.$route.query.folder_id});
                    this.fetchFolderBreadcrumb(this.$route.query.folder_id);
                    return;
                }
                this.queryServer();
            },
            fetchFolderBreadcrumb(folderId) {
                if (folderId === 0) {
                    this.folderBreadcrumb = [
                        {id: 0, name: 'Home'},
                    ];
                    return;
                }
                axios.get(route('api.media.folders.breadcrumb', {folder: folderId}))
                    .then(response => {
                        this.folderBreadcrumb = response.data;
                    });
            },
            handleSizeChange(event) {
                console.log('per page :' + event);
                this.tableIsLoading = true;
                this.queryServer({per_page: event});
            },
            handleCurrentChange(event) {
                console.log('current page :' + event);
                this.tableIsLoading = true;
                this.queryServer({page: event});
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({order_by: event.prop, order: event.order,});
            },
            performSearch(query) {
                console.log('searching:' + query);
                this.tableIsLoading = true;
                this.queryServer({search: query});
            },
            enterFolder(scope) {
                this.tableIsLoading = true;
                this.queryServer({folder_id: scope.row.id});
                this.folderId = scope.row.id;
                this.$router.push({ query: { folder_id: scope.row.id }});
                this.fetchFolderBreadcrumb(scope.row.id);
            },
            insertMedia(scope) {
                this.$events.emit(this.eventName, scope.row);
            },
            handleSelectionChange(selectedMedia) {
                this.selectedMedia = selectedMedia;
            },
            loadEditForm(scope) {
                console.log('clicked edit to' + scope.row.id);
                this.$router.push({name: 'admin.media.media.edit', params: {mediaId: scope.row.id}})
            },
            showEditFolder(scope) {
                this.$events.emit('editFolderWasClicked', scope);
            },
            changeRoot(folderId, index) {
                this.tableIsLoading = true;
                this.queryServer({folder_id: folderId});
                this.folderId = folderId;
                if (folderId === 0) {
                    this.$router.push({ query: {}});
                } else {
                    this.$router.push({ query: { folder_id: folderId }});
                }

                this.fetchFolderBreadcrumb(folderId);
            },
            batchDelete() {
                this.$confirm(this.trans('core.modal.confirmation-message'), this.trans('core.modal.title'), {
                    confirmButtonText: this.trans('core.button.delete'),
                    cancelButtonText: this.trans('core.button.cancel'),
                    type: 'warning'
                })
                    .then(() => {
                        this.filesAreDeleting = true;
                        axios.post(route('api.media.media.batch-destroy'), {
                            files: this.selectedMedia
                        })
                            .then(response => {
                                this.$message({
                                    type: 'success',
                                    message: response.data.message
                                });
                                this.filesAreDeleting = false;
                                this.$refs.mediaTable.clearSelection();
                                this.queryServer();
                            });
                    })
                    .catch(() => {
                        this.$message({
                            type: 'info',
                            message: this.trans('core.delete cancelled')
                        });
                    });
            },
        },
        mounted() {
            if (window.AsgardCMS.filesystem === 's3') {
                this.canEditFolders = false;
            }
            this.selectedMedia.length = 0;
            this.fetchMediaData();
            this.$events.listen('fileWasUploaded', eventData => {
                this.tableIsLoading = true;
                this.queryServer({folder_id: eventData.data.folder_id});
            });
            this.$events.listen('folderWasCreated', eventData => {
                this.tableIsLoading = true;
                this.queryServer({folder_id: eventData.data.folder_id});
            });
            this.$events.listen('folderWasUpdated', eventData => {
                this.tableIsLoading = true;
                this.queryServer({folder_id: eventData.data.folder_id});
            });
        }
    }
</script>
