<template>
    <div class="row" style="margin-top: -35px;">
        <div class="col-xs-12">
            <div class="sc-table">
                <div class="el-row">
                    <div class="title">
                        <h3 v-if="singleModal === false">{{ trans('media.title.media') }}</h3>
                        <div class="media-breadcrumb">
                            <el-breadcrumb v-if="!singleModal" separator="/">
                                <el-breadcrumb-item>
                                    <a href="/backend">Home</a>
                                </el-breadcrumb-item>
                                <el-breadcrumb-item :to="{name: 'admin.media.media.index'}">
                                    {{ trans('media.breadcrumb.media') }}
                                </el-breadcrumb-item>
                            </el-breadcrumb>
                        </div>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-body">
                        <upload-zone v-if="showUploadZone" :parent-id="folderId"></upload-zone>
                        <div class="tool-bar el-row" style="padding-bottom: 20px;">
                            <div class="actions el-col el-col-19">
                                <new-folder :parent-id="folderId"></new-folder>
                                <el-button type="primary" @click="toggleUploadZone">
                                    {{ trans('media.upload file') }}
                                </el-button>
                                <el-button :disabled="selectedMedia.length === 0" type="warning" @click="showMoveMedia">
                                    {{ trans('core.move') }}
                                </el-button>
                                <el-button :disabled="selectedMedia.length === 0" :loading="filesAreDeleting" type="danger" @click.prevent="batchDelete">
                                    {{ trans('core.button.delete') }}
                                </el-button>
                            </div>
                            <div class="search el-col el-col-5">
                                <el-input v-model="searchQuery" prefix-icon="el-icon-search" @keyup.native="performSearch"></el-input>
                            </div>
                        </div>
                        <el-row>
                            <el-col :span="24">
                                <el-breadcrumb separator="/" style="margin-bottom: 20px;">
                                    <el-breadcrumb-item v-for="(folder, index) in folderBreadcrumb" :key="folder.id" @click.native="changeRoot(folder.id, index)">
                                        {{ folder.name }}
                                    </el-breadcrumb-item>
                                </el-breadcrumb>
                            </el-col>
                        </el-row>
                        <el-table
                            ref="mediaTable"
                            v-loading.body="tableIsLoading"
                            :data="media"
                            stripe
                            style="width: 100%"
                            @sort-change="handleSortChange"
                            @selection-change="handleSelectionChange"
                        >
                            <el-table-column type="selection" width="55"></el-table-column>
                            <el-table-column label="" width="150">
                                <template slot-scope="scope">
                                    <template v-if="scope.row.is_image">
                                        <img :src="scope.row.small_thumb" alt="" class="img-responsive">
                                    </template>
                                    <template v-else-if="scope.row.is_folder">
                                        <i class="fa fa-2x fa-folder"></i>
                                    </template>
                                    <template v-else>
                                        <i v-if="scope.row.fa_icon" :class="scope.row.fa_icon" class="fa fa-2x"></i>
                                        <i v-else class="fa fa-2x fa-file"></i>
                                    </template>
                                </template>
                            </el-table-column>
                            <el-table-column :label="trans('media.table.filename')" prop="filename" sortable="custom">
                                <template slot-scope="scope">
                                    <template v-if="scope.row.is_folder">
                                        <strong style="cursor: pointer;" @click="enterFolder(scope)">
                                            {{ scope.row.filename }}
                                        </strong>
                                    </template>
                                    <template v-else>
                                        <a href="#" @click.prevent="goToEdit(scope)">
                                            {{ scope.row.filename }}
                                        </a>
                                    </template>
                                </template>
                            </el-table-column>
                            <el-table-column :label="trans('core.table.created at')" prop="created_at" sortable="custom" width="150"></el-table-column>
                            <el-table-column label="" prop="actions" width="150">
                                <template slot-scope="scope">
                                    <div class="pull-right">
                                        <el-button v-if="singleModal && !scope.row.is_folder" type="primary" size="small" @click.prevent="insertMedia(scope)">
                                            {{ trans('media.insert') }}
                                        </el-button>
                                        <div v-if="!singleModal">
                                            <el-button-group>
                                                <edit-button v-if="!scope.row.is_folder" :to="{name: 'admin.media.media.edit', params: {mediaId: scope.row.id}}"></edit-button>
                                                <el-button v-if="scope.row.is_folder && canEditFolders" size="mini" @click.prevent="showEditFolder(scope)">
                                                    <i class="fa fa-pencil"></i>
                                                </el-button>
                                                <delete-button :scope="scope" :rows="media"></delete-button>
                                            </el-button-group>
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="pagination-wrap" style="text-align: center; padding-top: 20px;">
                            <el-pagination
                                :current-page.sync="meta.current_page"
                                :page-sizes="[10, 20, 30, 50, 100]"
                                :page-size="parseInt(meta.per_page)"
                                :total="meta.total"
                                layout="total, sizes, prev, pager, next, jumper"
                                @size-change="handleSizeChange"
                                @current-change="handleCurrentChange"
                            ></el-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <rename-folder></rename-folder>
        <move-dialog></move-dialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import debounce from 'lodash/debounce';
    import MoveDialog from './MoveMediaDialog.vue';
    import NewFolder from './NewFolder.vue';
    import RenameFolder from './RenameFolder.vue';
    import UploadZone from './UploadZone.vue';
    import DeleteButton from '../../../../Core/Assets/js/components/DeleteComponent.vue';
    import EditButton from '../../../../Core/Assets/js/components/EditButtonComponent.vue';
    import ShortcutHelper from '../../../../Core/Assets/js/mixins/ShortcutHelper';

    export default {
        components: { MoveDialog, NewFolder, RenameFolder, UploadZone, DeleteButton, EditButton },
        mixins: [ShortcutHelper],
        props: {
            singleModal: { default: false, type: Boolean },
            eventName: { default: null, type: String },
        },
        data() {
            return {
                media: [],
                tableIsLoading: false,
                meta: {
                    current_page: 1,
                    per_page: 30,
                },
                order_meta: {
                    order_by: '',
                    order: '',
                },
                links: {},
                searchQuery: '',
                folderId: 0,
                selectedMedia: [],
                folderBreadcrumb: [
                    { id: 0, name: 'Home' },
                ],
                showUploadZone: false,
                filesAreDeleting: false,
                canEditFolders: true,
            };
        },
        watch: {
            '$route': 'fetchMediaData',
        },
        mounted() {
            if (window.AsgardCMS.filesystem === 's3') {
                this.canEditFolders = false;
            }
            this.fetchMediaData();
            this.$events.listen('fileWasUploaded', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer({ folder_id: eventData.data.folder_id });
            });
            this.$events.listen('folderWasCreated', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer({ folder_id: eventData.data.folder_id });
            });
            this.$events.listen('folderWasUpdated', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer({ folder_id: eventData.data.folder_id });
            });
            this.$events.listen('mediaWasMoved', (eventData) => {
                this.tableIsLoading = true;
                this.queryServer({ folder_id: eventData.folder_id });
                this.fetchFolderBreadcrumb(eventData.folder_id);
            });
        },
        methods: {
            queryServer(customProperties) {
                const properties = {
                    page: this.meta.current_page,
                    per_page: this.meta.per_page,
                    order_by: this.order_meta.order_by,
                    order: this.order_meta.order,
                    search: this.searchQuery,
                    folder_id: this.folderId,
                };

                axios.get(route('api.media.all-vue', { ...properties, ...customProperties }))
                    .then((response) => {
                        this.tableIsLoading = false;
                        this.media = response.data.data;
                        this.meta = response.data.meta;
                        this.links = response.data.links;
                        this.order_meta.order_by = properties.order_by;
                        this.order_meta.order = properties.order;
                    });
            },
            fetchMediaData() {
                this.tableIsLoading = true;
                this.meta.current_page = 1;
                if (this.$route.query.folder_id !== undefined) {
                    this.queryServer({ folder_id: this.$route.query.folder_id });
                    this.fetchFolderBreadcrumb(this.$route.query.folder_id);
                    return;
                }
                this.queryServer();
                this.fetchFolderBreadcrumb(0);
            },
            fetchFolderBreadcrumb(folderId) {
                if (folderId === 0) {
                    this.folderBreadcrumb = [
                        {
                            id: 0,
                            name: 'Home',
                        },
                    ];

                    return;
                }
                axios.get(route('api.media.folders.breadcrumb', { folder: folderId }))
                    .then((response) => {
                        this.folderBreadcrumb = response.data;
                    });
            },
            handleSizeChange(event) {
                console.log(`per page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ per_page: event });
            },
            handleCurrentChange(event) {
                console.log(`current page :${event}`);
                this.tableIsLoading = true;
                this.queryServer({ page: event });
            },
            handleSortChange(event) {
                console.log('sorting', event);
                this.tableIsLoading = true;
                this.queryServer({ order_by: event.prop, order: event.order });
            },
            performSearch: debounce(function (query) {
                console.log(`searching:${query.target.value}`);
                this.tableIsLoading = true;
                this.queryServer({ search: query.target.value });
            }, 300),
            enterFolder(scope) {
                this.tableIsLoading = true;
                this.folderId = scope.row.id;
                this.pushRoute({ query: { folder_id: scope.row.id } });
            },
            insertMedia(scope) {
                this.$events.emit(this.eventName, scope.row);
            },
            handleSelectionChange(selectedMedia) {
                this.selectedMedia = selectedMedia;
            },
            toggleUploadZone() {
                this.showUploadZone = !this.showUploadZone;
            },
            showEditFolder(scope) {
                this.$events.emit('editFolderWasClicked', scope.row);
            },
            showMoveMedia() {
                this.$events.emit('moveMediaWasClicked', this.selectedMedia);
            },
            changeRoot(folderId) {
                this.tableIsLoading = true;
                this.folderId = folderId;
                if (folderId === 0) {
                    this.pushRoute({ query: {} });
                } else {
                    this.pushRoute({ query: { folder_id: folderId } });
                }
            },
            batchDelete() {
                this.$confirm(this.trans('core.modal.confirmation-message'), this.trans('core.modal.title'), {
                        confirmButtonText: this.trans('core.button.delete'),
                        cancelButtonText: this.trans('core.button.cancel'),
                        type: 'warning',
                    })
                    .then(() => {
                        this.filesAreDeleting = true;
                        axios.post(route('api.media.media.batch-destroy'), {
                                files: this.selectedMedia,
                            })
                            .then((response) => {
                                this.$message({
                                    type: 'success',
                                    message: response.data.message,
                                });
                                this.filesAreDeleting = false;
                                this.$refs.mediaTable.clearSelection();
                                this.fetchMediaData();
                            });
                    })
                    .catch(() => {
                        this.$message({
                            type: 'info',
                            message: this.trans('core.delete cancelled'),
                        });
                    });
            },
            goToEdit(scope) {
                this.pushRoute({ name: 'admin.media.media.edit', params: { mediaId: scope.row.id } });
            },
        },
    };
</script>
