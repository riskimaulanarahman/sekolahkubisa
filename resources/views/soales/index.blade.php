@extends('layouts.default', ['sidebarSearch' => true])

@section('title', 'Soal')

@section('content')

    {{-- <h1 class="page-header">Module List</h1> --}}

    <!-- begin panel -->
    <div class="panel panel-info">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">List Soal </h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                    data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div id="gridSoal" style="height: 640px; width:100%;"></div>
            <div id="popup"></div>
            <div id="popupsoal"></div>
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
		
@endsection

@push('scripts')

<script>

    var store = new DevExpress.data.CustomStore({
        key: "id",
        load: function() {
            return sendRequest(apiurl + "/soales");
        },
        insert: function(values) {
            return sendRequest(apiurl + "/soales", "POST", values);
        },
        update: function(key, values) {
            return sendRequest(apiurl + "/soales/"+key, "PUT", values);
        },
        remove: function(key) {
            return sendRequest(apiurl + "/soales/"+key, "DELETE");
        }
    });

    function moveEditColumnToLeft(dataGrid) {
        dataGrid.columnOption("command:edit", { 
            visibleIndex: -1,
            width: 80 
        });
    }

    // attribute
    var dataGrid = $("#gridSoal").dxDataGrid({    
        dataSource: store,
        allowColumnReordering: true,
        allowColumnResizing: true,
        columnsAutoWidth: true,
        columnMinWidth: 80,
        wordWrapEnabled: true,
        showBorders: true,
        filterRow: { visible: true },
        filterPanel: { visible: true },
        headerFilter: { visible: true },
        selection: {
            mode: "multiple"
        },
        editing: {
            useIcons:true,
            mode: "form",
            allowAdding: true,
            allowUpdating: true,
            allowDeleting: true,
        },
        scrolling: {
            mode: "virtual"
        },
        columns: [
            { 
                dataField: "no_soal",
                caption: "No. Soal",
                width: 100,
                validationRules: [
                    { type: "required" }
                ]
            },
            { 
                caption: "Module",
                dataField: "module_id",
                width: 250,
                lookup: {
                    dataSource: listModule,  
                    valueExpr: 'id',
                    displayExpr: 'nama_module',
                },
                validationRules: [{ type: "required" }]
            },
            { 
                dataField: "soal",
                caption: "Soal",
                encodeHtml: false,
                validationRules: [
                    { type: "required" }
                ],
                cellTemplate: function(container, options) {
                    var text = options.data.soal;
                    if (text.length > 50) {
                        text = text.substr(0, 50) + "...";
                    }
                    $("<div>")
                        .text(text)
                        .appendTo(container);
                }
            },
            {
                caption: 'Aksi',
                formItem: {visible:false},
                width: 100,
                cellTemplate: function(container,options) {
                    $('<button class="btn btn-primary">Jawaban</button>').on('dxclick',function(evt){
                        evt.stopPropagation();
                        var soalid = options.data.id
                        var mode = 'edit'

                            popup.option({
                                contentTemplate: () => popupContentTemplate(soalid,mode),
                            });
                            popup.show();

                    }).appendTo(container);
                }
            },
            
        ],
        export: {
            enabled: false,
            fileName: "",
            excelFilterEnabled: true,
            allowExportSelectedData: true
        },
        onContentReady: function(e){
            moveEditColumnToLeft(e.component);
        },
        onEditorPreparing: function(e) {
            if (e.dataField == "soal" && e.parentType === "dataRow") {
                const defaultValueChangeHandler = e.editorOptions.onValueChanged;
                e.editorName = "dxHtmlEditor"; // Change the editor's type
                console.log(e)
                e.editorOptions = {
                    value: e.value,
                    toolbar: {
                        items: [
                            'undo', 'redo', 'separator',
                            'bold', 'italic', 'strike', 'underline', 'separator',
                            'alignLeft', 'alignCenter', 'alignRight', 'alignJustify', 'separator',
                        ]
                    }
                }
                // e.editorOptions = toolbar: { [
                //     'undo', 'redo', 'separator',
                //     'bold', 'italic', 'strike', 'underline', 'separator',
                //     'alignLeft', 'alignCenter', 'alignRight', 'alignJustify', 'separator',
                // ]},
                e.editorOptions.onValueChanged = function (args) {  // Override the default handler
                    defaultValueChangeHandler(args);
                }
            }
        },
        onToolbarPreparing: function(e) {
            dataGrid = e.component;
    
            e.toolbarOptions.items.unshift({						
                location: "after",
                widget: "dxButton",
                options: {
                    hint: "Refresh Data",
                    icon: "refresh",
                    onClick: function() {
                        dataGrid.refresh();
                    }
                }
            })
        },
    }).dxDataGrid("instance");

    const accordionItems = [
        {
            ID: 1,
            Title: '<i class="fa fa-clipboard-check"> Jawaban </i>',
        },
    ];

    const popupContentTemplate = function (soalid,mode) {

        const scrollView = $('<div />');

        scrollView.append(
            $("<div>").dxAccordion({
                dataSource: accordionItems,
                animationDuration: 600,
                selectedItems: [accordionItems[0]],
                collapsible: true,
                multiple: true,
                itemTitleTemplate: function (data) {
                    return '<small style="margin-bottom:10px !important ;">'+data.Title+'</small>'
                },
                itemTemplate: function (data) {
                    
                    if(data.ID == 1) {      
                        var store1 = new DevExpress.data.CustomStore({
                            key: "id",
                            load: function() {
                                return sendRequest(apiurl + "/jawaban/"+soalid);
                            },
                            insert: function(values) {
                                values.soal_id = soalid;
                                return sendRequest(apiurl + "/jawaban", "POST", values);
                            },
                            update: function(key, values) {
                                return sendRequest(apiurl + "/jawaban/"+key, "PUT", values);
                            },
                            remove: function(key) {
                                return sendRequest(apiurl + "/jawaban/"+key, "DELETE");
                            },
                        });       
                        return $("<div id='grid-jawaban'>").dxDataGrid({    
                            dataSource: store1,
                            allowColumnReordering: true,
                            allowColumnResizing: true,
                            columnsAutoWidth: true,
                            // columnHidingEnabled: true,
                            wordWrapEnabled: true,
                            showBorders: true,
                            filterRow: { visible: true },
                            filterPanel: { visible: false },
                            headerFilter: { visible: true },
                            editing: {
                                useIcons:true,
                                mode: "cell",
                                allowAdding: (role == 'admin' || role == 'staff') ? true : false,
                                allowUpdating: (role == 'admin' || role == 'staff') ? true : false,
                                allowDeleting: (role == 'admin') ? true : false,
                            },
                            searchPanel: {
                                visible: true,
                                width: 240,
                                placeholder: 'Search...',
                            },
                            scrolling: {
                                rowRenderingMode: 'virtual',
                            },
                            paging: {
                                pageSize: 20,
                            },
                            pager: {
                                visible: false,
                                // allowedPageSizes: [10, 20, 'all'],
                                showPageSizeSelector: true,
                                showInfo: true,
                                showNavigationButtons: true,
                                displayMode: 'compact'
                            },
                            columns: [
                              
                                { 
                                    dataField: "jawaban",
                                },
                                { 
                                    dataField: "benar",
                                    dataType: "boolean"
                                },
                            
                            ],
                            onInitialized: function(e) {
                                dxGridInstance1 = e.component;
                            },
                            onContentReady: function(e){
                                moveEditColumnToLeft(e.component);
                            },
                            onToolbarPreparing: function(e) {
                                e.toolbarOptions.items.unshift({						
                                    location: "after",
                                    widget: "dxButton",
                                    options: {
                                        hint: "Refresh Data",
                                        icon: "refresh",
                                        onClick: function() {
                                            dxGridInstance1.refresh();
                                        }
                                    }
                                })
                            },
                        })
                    }
                }
            })
        );



        scrollView.dxScrollView({
            width: '100%',
            height: '100%',
        })

        return scrollView;

    };

const popupsoalContentTemplate = function (soalid,mode) {

    const scrollView = $('<div />');

    const markup = `
    <h2>
        <img src="images/widgets/HtmlEditor.svg" alt="HtmlEditor">
        Formatted Text Editor (HTML Editor)
    </h2>
    <br>
    <p>DevExtreme JavaScript HTML Editor is a client-side WYSIWYG text editor that allows its users to format textual and visual content and store it as HTML or Markdown.</p>
    <p>Supported features:</p>
    `;
    scrollView.append(
        $("<div id='soalEditor'>").dxHtmlEditor({
            value: markup,
            readOnly: false,
            toolbar: {
                items: [
                    'undo', 'redo', 'separator',
                    'bold', 'italic', 'strike', 'underline', 'separator',
                    'alignLeft', 'alignCenter', 'alignRight', 'alignJustify', 'separator',
                ],
            },
        })
    );

    scrollView.append(
            $("<div id='soalForm'>").dxForm({
                onInitialized: function(e) {
                    dxFormInstance = e.component;
                },
                labelMode : 'floating',
                readOnly: false,
                showColonAfterLabel: true,
                showValidationSummary: false,
                items: [ {
                itemType: 'group',
                caption: '',
                colCount : 2,
                items: [
                        {
                            dataField: 'keterangan_daftar_pengurusan',
                            label: {text: 'Keterangan'},
                            colSpan: 2,
                            editorType: 'dxHtmlEditor',
                            editorOptions: {
                                height: 90
                            },
                        }, 
                    ],
                }, 
                {
                    itemType: "group",
                    caption: "",
                    colCount:10,
                    items: [{
                        itemType: 'button',
                        horizontalAlignment: 'left',
                        visible: (mode=='edit') ?true:false,
                        disabled: (role == 'admin' || role == 'staff') ? false : true,
                        buttonOptions: {
                            text: 'Ubah',
                            type: 'default',
                            onClick: function(e) {

                                var values = dxFormInstance.option("formData");
                                delete values.created_at
                                delete values.updated_at
                                delete values.deleted_status
                                
                                // console.log(apiurl + "/dokumen/"+daftarid, "PUT", values)
                                sendRequest(apiurl + "/dokumen/"+daftarid, "PUT", values);
                                
                                popup.hide();
                                dataGrid.refresh();
                            },
                            useSubmitBehavior: true,
                        },
                    },{
                        itemType: 'button',
                        horizontalAlignment: 'left',
                        visible: (mode=='edit') ?false:true,
                        buttonOptions: {
                            text: 'Simpan',
                            type: 'success',
                            onClick: function(e) {

                                // console.log(e);

                                var values = dxFormInstance.option("formData");
                                values.createdby = valuserid
                                delete values.created_at
                                delete values.updated_at
                                delete values.deleted_status

                                var result = dxFormInstance.validate();
                                if(result.isValid) {
                                    sendRequest(apiurl + "/dokumen", "POST", values).then(function(response){
                                        if(response.status == 'prompt') {
                                            // alert('data sama')
                                            console.log('from simpan :')
                                            console.log(response.data)
                                            popprompt.option({
                                                contentTemplate: () => popupContentPrompt(response.data),
                                            });
                                            popprompt.show();
                                        } else {

                                            dataGrid.refresh();
                                            loadPanel.show();

                                            
                                            setTimeout(() => {
                                            //     if(response.status !== 'error') {
                                            //         $('#btndaftarid'+response.data.id).trigger('click')
                                            //     }
                                            loadPanel.hide();
                                            }, 5000);
                                        }
                                    });
                                } else {
                                    DevExpress.ui.dialog.alert("Your form is not complete or has invalid value, please recheck before submit","Error");
                                }

                            },
                            useSubmitBehavior: true,
                        },
                    },{
                        itemType: 'button',
                        horizontalAlignment: 'left',
                        buttonOptions: {
                            text: 'Batal',
                            type: 'danger',
                            onClick: function(e) {
                                popup.hide()
                                // dataGrid.refresh();
                            },
                            useSubmitBehavior: true,
                            
                        },
                    }],
                }],
                
            }),
            $("<hr>"),
            
        );

    scrollView.append(
        $("<div>").dxAccordion({
            dataSource: accordionItems,
            animationDuration: 600,
            selectedItems: [accordionItems[0],accordionItems[1],accordionItems[2]],
            collapsible: true,
            multiple: true,
            itemTitleTemplate: function (data) {
                return '<small style="margin-bottom:10px !important ;">'+data.Title+'</small>'
            },
            itemTemplate: function (data) {
                
                if(data.ID == 1) {      
                    var store1 = new DevExpress.data.CustomStore({
                        key: "id",
                        load: function() {
                            return sendRequest(apiurl + "/jawaban/"+soalid);
                        },
                        insert: function(values) {
                            values.soal_id = soalid;
                            return sendRequest(apiurl + "/jawaban", "POST", values);
                        },
                        update: function(key, values) {
                            return sendRequest(apiurl + "/jawaban/"+key, "PUT", values);
                        },
                        remove: function(key) {
                            return sendRequest(apiurl + "/jawaban/"+key, "DELETE");
                        },
                    });       
                    return $("<div id='grid-jawaban'>").dxDataGrid({    
                        dataSource: store1,
                        allowColumnReordering: true,
                        allowColumnResizing: true,
                        columnsAutoWidth: true,
                        // columnHidingEnabled: true,
                        wordWrapEnabled: true,
                        showBorders: true,
                        filterRow: { visible: true },
                        filterPanel: { visible: false },
                        headerFilter: { visible: true },
                        editing: {
                            useIcons:true,
                            mode: "batch",
                            allowAdding: (role == 'admin' || role == 'staff') ? true : false,
                            allowUpdating: (role == 'admin' || role == 'staff') ? true : false,
                            allowDeleting: (role == 'admin') ? true : false,
                        },
                        searchPanel: {
                            visible: true,
                            width: 240,
                            placeholder: 'Search...',
                        },
                        scrolling: {
                            rowRenderingMode: 'virtual',
                        },
                        paging: {
                            pageSize: 20,
                        },
                        pager: {
                            visible: false,
                            // allowedPageSizes: [10, 20, 'all'],
                            showPageSizeSelector: true,
                            showInfo: true,
                            showNavigationButtons: true,
                            displayMode: 'compact'
                        },
                        columns: [
                            // { 
                            //     caption: "Jenis Dokumen",
                            //     dataField: "id_ref_dokumen_klien",
                            //     lookup: {
                            //         dataSource: listDokumenklien,  
                            //         valueExpr: 'id',
                            //         displayExpr: 'nama_dokumen_klien',
                            //     },
                            //     validationRules: [{ type: "required" }]
                            // },
                            // {
                            //     dataField: "foto_dokumen_klien",
                            //     allowFiltering: false,
                            //     allowSorting: false,
                            //     cellTemplate: cellTemplate,
                            //     editCellTemplate: editCellTemplate,
                            // },
                            { 
                                dataField: "jawaban",
                            },
                            { 
                                dataField: "benar",
                                dataType: "boolean"
                            },
                            // { 
                            //     dataField: "nomor_dokumen",
                            // },
                            // { 
                            //     dataField: "tanggal_penyerahan",
                            //     dataType: "date",
                            //     format: "dd-MM-yyyy",
                            // },
                            // { 
                            //     dataField: "lokasi_penyerahan_dokumen_klien",
                            // },
                        
                        ],
                        onInitialized: function(e) {
                            dxGridInstance1 = e.component;
                        },
                        onContentReady: function(e){
                            moveEditColumnToLeft(e.component);
                        },
                        onToolbarPreparing: function(e) {
                            e.toolbarOptions.items.unshift({						
                                location: "after",
                                widget: "dxButton",
                                options: {
                                    hint: "Refresh Data",
                                    icon: "refresh",
                                    onClick: function() {
                                        dxGridInstance1.refresh();
                                    }
                                }
                            })
                        },
                    })
                }
            }
        })
    );



    scrollView.dxScrollView({
        width: '100%',
        height: '100%',
    })

    return scrollView;

};


const popup = $('#popup').dxPopup({
    contentTemplate: popupContentTemplate,
    container: '.content',
    showTitle: true,
    title: 'Form Jawaban',
    visible: false,
    dragEnabled: false,
    hideOnOutsideClick: false,
    showCloseButton: true,
    fullScreen : false,

}).dxPopup('instance');

const popupsoal = $('#popupsoal').dxPopup({
    contentTemplate: popupContentTemplate,
    container: '.content',
    showTitle: true,
    title: 'Form Soal',
    visible: false,
    dragEnabled: false,
    hideOnOutsideClick: false,
    showCloseButton: true,
    fullScreen : false,

}).dxPopup('instance');

</script>

@endpush
