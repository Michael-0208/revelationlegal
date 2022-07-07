/*!
 * bootstrap-treetable - jQuery plugin for bootstrapview treetable
 *
 * Copyright (c) 2007-2015 songhlc
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://github.com/songhlc
 *
 * Version:  1.0.0
 *
 */
(function($){
	$.fn.bstreetable = function(options){
		$window = window;
		var element = this;
		var $container;
		var settings = {
			container:window,
			data:[],
			extfield:[],//{title:"column name",key:"",type:"input"}
			nodeaddEnable: permissionCreate == 1 ? true : false,
			maxlevel:4,
			nodeaddCallback:function(data,callback){},
			noderemoveCallback:function(data,callback){},
			nodeupdateCallback:function(data,callback){},
            customalert:function(msg){
                alert(msg);
            },
            customconfirm:function(msg){
                return confirm(msg);
            },
            text:{
                NodeDeleteText:"Are You Sure To Delete This Question?"
            }
		};
		var TREENODECACHE = "treenode";
		var language ={};
		language.addchild = "Add";
		if(options) {
            $.extend(settings, options); 
        }
        /* Cache container as jQuery as object. */
        $container = (settings.container === undefined ||
                      settings.container === window) ? $window : $(settings.container);
        /*render data*/
        // .append(`<button class="btn btn-secondary btn-sm j-collapseAll">Collapse All</button>`).append(`<button class="btn btn-secondary btn-sm j-expandAll" >Expand All</button>`)
        if (permissionCreate == 0) {
            var dom_addFirstLevel = $("<div class='tt-operation m-b-sm'></div>").append(`<button class="j-moreinfo" >Show Definitions</button>`).append(`<button class="j-treechart" >Tree Chart</button>`);
            if (permissionExport == 1) {
                dom_addFirstLevel.append(`<button class="btn-sm "  onclick="exportExcel();"><i class="fa fa-download"></i>&nbsp;Export</button>`); 
            }
        } else {
            var dom_addFirstLevel = $("<div class='tt-operation m-b-sm'></div>")/*.append($("<button class='j-addClass'><i class='fa fa-plus'></i>&nbsp;Add First Level Branch</button>"))*/.append(`<button class="j-moreinfo" >Show Definitions</button>`).append(`<button class="j-treechart" >Tree Chart</button>`); 
            if (permissionExport == 1) {
                dom_addFirstLevel.append(`<button class="exportbtn "  onclick="exportExcel();"><i class="fa fa-download"></i>&nbsp;Export</button>`);  
            }
        }
        var dom_table = $("<div class='tt-body'></div>");
        var dom_header = $("<div class='tt-header'></div>");
        /*renderHeader*/
        renderHeader(dom_header);
        element.html('').append(dom_addFirstLevel).append(dom_header);
        var treeData = {};
        /*render firstlevel tree*/
        for(var i=0;i<settings.data.length;i++){
        	var row = settings.data[i];
        	//render first level row while row.pid equals 0 or null or undefined
        	if(!row.pid){
                generateTreeNode(dom_table,row,1);
        		treeData[row.id] = row;
        	}
        }

        element.append(dom_table);
        /*delegate click event*/
        element.delegate(".j-expend","click",function(event){
        	if(event.target.classList[0]=="fa"){
        		var treenode = treeData[$(this).attr('data-id')];
	        	toggleicon($(this));
	        	if($(this).parent().attr('data-loaded')){
	        		toggleExpendStatus($(this),treenode);
	        	}
	        	else{
		        	loadNode($(this),treenode);
	        	}
        	}
        });
        element.delegate(".j-addClass","click",function(){
            var curElement = $(".tt-body");
            var row = {id:"",name:"",pid:0};
            var curLevel = 1;
            generateTreeNode(curElement,row,curLevel,true);
            $('#additem_pid').val(row.pid);
            $('#createTaxonomyModal').modal('show');
        });
        // delegate more info event
        element.delegate(".j-moreinfo", "click", function() {
            if (moreInfo == 0) {
                moreInfo = 1;
                $('.extra_Taxonomyinfo').css('opacity', '1');
                $('.extra_Taxonomyinfo').css('height', 'auto');
                $('.j-moreinfo').html('Hide Definitions');
            } else {
                moreInfo = 0;
                $('.extra_Taxonomyinfo').css('opacity', '0');
                $('.extra_Taxonomyinfo').css('height', '0');
                $('.j-moreinfo').html('Show Definitions');
            } 
        });
        // delegate edit event
        element.delegate(".j-edit", "click", function (event) {
            $editModal = $('#editTaxonomyModal');
            console.log(itemData);
            // Format the input boxes
            $editModal.find('input').val('');
            $('#item_enabled').attr('checked', 'false');
            var parentDom = $(this).parents(".class-level-ul");
            var data_id = parentDom.attr('data-id');
            // Set the value of input boxes with the data
            var itemData = questionData.filter(obj => {return obj.id == data_id;});
            $('#item_id').val(data_id);
            if (itemData[0].question_enabled == 1) {
                $('#item_enabled').attr('checked', 'true');
            }
            $('#item_code').val(itemData[0].question_code);
            $('#item_desc').val(itemData[0].name);
            $('#sort_id').val(itemData[0].sort_id);
            (itemData[0].lead_codes !== null ) ? $('#lead_code').val(itemData[0].lead_codes):'';
            $('#item_extra').val(itemData[0].question_extra);
            $('#item_desc_alt').val(itemData[0].question_desc_alt);
            $('#item_extra_alt').val(itemData[0].question_extra_alt);
            $('#item_prox').val(itemData[0].question_proximity_factor);
            $editModal.modal('show');
        });
        /*delegate remove event*/
        element.delegate(".j-remove","click",function(event){
        	//TODO: Need to determine whether there are child nodes, if they exist, they are not allowed to be deleted
            var parentDom = $(this).parents(".class-level-ul");
            var isRemoveAble = false;
            if(parentDom.attr("data-loaded")=="true"){
                if(parentDom.parent().find(".class-level").length>0){
                    settings.customalert("Please remove the linked question(s) first.");
                    return;
                }
                else{
                    isRemoveAble = true;
                }
            }
            else{
                // If it is a newly added node, set it to be deleted, otherwise it needs to be expanded and then deleted
                if(parentDom.attr("data-id")){
                    var existChild = false;
                    for(var i=0;i<settings.data.length;i++){
                        if(settings.data[i].pid==parentDom.attr("data-id")){
                            existChild = true;
                            break;
                        }
                    }
                    if(existChild){
                        settings.customalert("Please remove the linked question(s) first.");
                        return;
                    }
                    else{
                        isRemoveAble = true;
                    }
                }
                else{
                    isRemoveAble = true;
                }
            }
            if(isRemoveAble){
                var that = $(this);
                // Delete confirmation
                if(settings.customconfirm(settings.text.NodeDeleteText)){
                    /*trigger remove callback*/
                    settings.noderemoveCallback(that.parents(".class-level-ul").attr("data-id"),function(){
                        that.parents(".class-level-ul").parent().remove();
                    });
                }
            }
        });
        /*delegate addchild event*/
        element.delegate(".j-addChild","click",function(){
        	var curElement = $(this).closest(".class-level");
            var requiredInput = curElement.find(".form-control*[required]");
            var hasError = false;
            requiredInput.each(function(){
                if($(this).val()==""){
                    $(this).addClass("has-error");
                    hasError = true;
                }
            });
            if(!hasError){
                var pid = curElement.find(".j-expend").attr("data-id");
                var curLevel = $(this).parents(".class-level-ul").attr("data-level")-0+1;
                var row = {id:"",name:"",pid:pid};
                generateTreeNode(curElement,row,curLevel);
                $('#additem_pid').val(row.pid);
                settings.nodeaddCallback(row, function() {});
                // $('#createTaxonomyModal').modal('show');
            }

        });
        // delegate expandAll event
        element.delegate(".j-expandAll", "click", function () {
            expendNode();
        });
        // delegate collapseAll event
        element.delegate(".j-collapseAll", "click", function () {

        });
        // delegate TreeChart event
        element.delegate(".j-treechart", "click", function () {
            $('#taxonomyTable').hide();
            $('.treeArea').show();
        });
        // delegate Export event
        element.delegate(".j-export", "click", function () {

        });
        /*Focus event*/
        element.delegate(".form-control","focus",function(){
            // In the blur event, if the input is empty, the has-error style will be added
            $(this).parent().removeClass("has-error");
            var curElement = $(this);
            var data = {};
            /*Too many parents are used in the code and need to be refactored*/
            data.id = curElement.parent().parent().attr("data-id");
            var parentUl = curElement.closest(".class-level-ul");
            data.pid = parentUl.attr("data-pid");
            data.innercode = parentUl.attr("data-innercode");
            data.pinnercode = curElement.parents(".class-level-"+(parentUl.attr("data-level")-1)).children("ul").attr("data-innercode");
            data.oldname = curElement.attr("data-oldval");
            if(!data.id&&!curElement.attr("data-oldval")){
                settings.nodeaddCallback(data,function(_data){
                    if(_data){
                        curElement.parent().attr("data-id",_data.id);
                        curElement.parent().parent().attr("data-id",_data.id);
                        curElement.parent().parent().attr("data-innercode",_data.innercode);
                        curElement.attr("data-oldval",curElement.val());
                    }
                });
            }
        });
        /*delegate lose focus event*/
        element.delegate(".form-control","blur",function(){
            var curElement = $(this);
            var data = {};
            /*Too many parents are used in the code and need to be refactored*/
            data.id = curElement.parent().parent().attr("data-id");
            var parentUl = curElement.closest(".class-level-ul");
            data.pid = parentUl.attr("data-pid");
            data.innercode = parentUl.attr("data-innercode");
            data.pinnercode = curElement.parents(".class-level-"+(parentUl.attr("data-level")-1)).children("ul").attr("data-innercode");
            data.oldname = curElement.attr("data-oldval");

            parentUl.find(".form-control").each(function(){
                data[$(this).attr("name")]=$(this).val();
            });
            if(!data.id&&!curElement.attr("data-oldval")){
                // settings.nodeaddCallback(data,function(_data){
                //     if(_data){
                //         curElement.parent().attr("data-id",_data.id);
                //         curElement.parent().parent().attr("data-id",_data.id);
                //         curElement.parent().parent().attr("data-innercode",_data.innercode);
                //         curElement.attr("data-oldval",curElement.val());
                //     }
                // });
            }
            else if(curElement.attr("data-oldval")!=curElement.val()){
                let editConfirm = confirm("Your changes will not be saved, are you sure to leave?");
                if (editConfirm === true) {
                    $(this).val(data.oldname);
                } else {
                    settings.nodeupdateCallback(data,function(){
                        curElement.attr("data-oldval",curElement.val());
                    });
                }
            }

        });
        // Keypress
        element.delegate("input.form-control", "keypress", function (e) {
            var keycode = parseInt(e.keyCode ? e.keyCode : e.which);

            var curElement = $(this);
            var data = {};
            /*Too many parents are used in the code and need to be refactored*/
            data.id = curElement.parent().parent().attr("data-id");
            var parentUl = curElement.closest(".class-level-ul");
            data.pid = parentUl.attr("data-pid");
            data.innercode = parentUl.attr("data-innercode");
            data.pinnercode = curElement.parents(".class-level-"+(parentUl.attr("data-level")-1)).children("ul").attr("data-innercode");
            data.oldname = curElement.attr('data-oldval');

            parentUl.find(".form-control").each(function(){
                data[$(this).attr("name")]=$(this).val();
            });
            // if(!data.id&&!curElement.attr("data-oldval")){
            //     settings.nodeaddCallback(data,function(_data){
            //         if(_data){
            //             curElement.parent().attr("data-id",_data.id);
            //             curElement.parent().parent().attr("data-id",_data.id);
            //             curElement.parent().parent().attr("data-innercode",_data.innercode);
            //             curElement.attr("data-oldval",curElement.val());
            //         }
            //     });
            // }
            if(curElement.attr("data-oldval") == ''){
                if (keycode == 13) {
                    settings.nodeaddCallback(data,function(){
                        // curElement.attr("data-oldval",curElement.val());
                    });
                }
            }
        });

        function renderHeader(_dom_header){
        	var dom_row = $('<div></div>');
        	dom_row.append($("<span class='maintitle'></span>").text(settings.maintitle));
        	dom_row.append($("<span></span>"));
        	//render extfield
    		for(var j=0;j<settings.extfield.length;j++){
    			var column = settings.extfield[j];
    			$("<span></span>").css("min-width","166px").text(column.title).appendTo(dom_row);
    		}
    		dom_row.append($("<span class='textalign-center'>Operation</span>"));
    		_dom_header.append(dom_row);
        }

        function generateColumn(row,extfield){
        	var generatedCol;
        	switch(extfield.type){
        		case "input":generatedCol=$("<input type='text' class='form-control input-sm'/>").val(row[extfield.key]).attr("data-oldval",row[extfield.key]).attr("name",extfield.key);break;
        		default:generatedCol=$("<span></span>").text(row[extfield.key]);break;
        	}
        	return generatedCol;
        }
        function toggleicon(toggleElement){
        	var _element = toggleElement.find(".fa");
        	if(_element.hasClass("fa-plus-square-o")){
        		_element.removeClass("fa-plus-square-o").addClass("fa-minus-square-o");
        		toggleElement.parent().addClass("selected");
        	}else{
        		_element.removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
        		toggleElement.parent().removeClass("selected")
        	}
        }
		function toggleExpendStatus(curElement){
			if(curElement.find(".fa-minus-square-o").length>0){
                 curElement.parent().parent().find(".class-level").removeClass("rowhidden");
            }
            else{
                curElement.parent().parent().find(".class-level").addClass("rowhidden");
            }

		}
		function collapseNode(){
            $('.tt-body').empty();
            treeData = {};
            for(var i=0;i<settings.data.length;i++){
                var row = settings.data[i];
                //render first level row while row.pid equals 0 or null or undefined
                if (!row.pid) {
                    generateTreeNode(dom_table,row,1);
                    treeData[row.id] = row;
                }
            }
		}

		function expendNode(){
            $('.tt-body').empty();
            treeData = {};
            for(var i=0;i<settings.data.length;i++){
                var row = settings.data[i];
                //render first level row while row.pid equals 0 or null or undefined
                if (!row.pid) {
                    generateTreeNode(dom_table,row,1);
                    treeData[row.id] = row;
                }
            }
            for (const field in treeData) {
                for (let i = 0; i < settings.data.length; i++) {
                    const row = settings.data[i];
                    if (treeData[field].id == row.pid) {
                        let newLevel = $(`.j-expend[data-id="${treeData[field].id}"]`).parent().attr('data-level') + 1;
                        generateTreeNode($(`.j-expend[data-id="${treeData[field].id}"]`).parent().parent(), row, 2);
                        $(`.j-expend[data-id="${treeData[field].id}"]`).find('.fa').removeClass("fa-plus-square-o").addClass("fa-minus-square-o");
                        $(`.j-expend[data-id="${treeData[field].id}"]`).parent().attr('data-loaded',true);
                        treeData[row.id] = row;
                    }
                }
            }
		}

		function loadNode(loadElement,parentNode){
			var curElement = loadElement.parent().parent();
        	var curLevel = loadElement.parent().attr("data-level")-0+1;

            console.log(settings.data);
            

            function compare(a, b) {  

                if (a.sort_id < b.sort_id) {
                    return -1;
                }
                if (a.sort_id > b.sort_id) {
                    return 1;
                }
            
                
                return 0;
            }

           

            settings.data.sort(compare);


        	// TODO: Delete the loaded data from the list to reduce the number of cycles
        	if(parentNode && parentNode.id){
                for(var i=0;i<settings.data.length;i++){
    	        	var row = settings.data[i];
    	        	//render first level row while row.pid equals 0 or null or undefined
    	        	if(row.pid==parentNode.id){
    	        		generateTreeNode(curElement,row,curLevel);
                        //cache treenode
                        treeData[row.id] = row;
    	        	}
    	        }
            }
            loadElement.parent().attr('data-loaded',true);

		}

        function generateTreeNode(curElement,row,curLevel,isPrepend){
            var dom_row = $('<div class="class-level class-level-'+curLevel+'"></div>');
            var dom_ul =$('<ul class="class-level-ul"></ul>');
            if (curLevel > 1) {
                dom_ul.attr("data-pid", row.pid).attr("data-level", curLevel).attr("data-id", row.id).attr("data-sort-id", row.sort_id);
            } else {
                dom_ul.attr("data-pid", row.pid).attr("data-level", curLevel).attr("data-id", row.id);
            }

            row.innercode&&dom_ul.attr("data-innercode",row.innercode);
            let styleInfo = ``;
            if (moreInfo == 1) {
                styleInfo = `style="height:auto;opacity:1;"`;
            }
            let extra_info = `<div class="extra_Taxonomyinfo row" ${styleInfo}>
                                    <div class="code">${row.question_code}</div>
                                    <div class="col-12 desc">${row.question_extra}</div>
                                </div>`;
            if(curLevel-0>=settings.maxlevel){
                if (permissionUpdate == 1) {
                    $('<li class="j-expend"></li>').append('<label class="fa p-xs"></label>').append(`<strong class="code_str" style="margin-left:10px;margin-right:10px;"></strong>`).append($("<input type='text' class='form-control input-sm' required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                } else {
                    $('<li class="j-expend"></li>').append('<label class="fa p-xs"></label>').append(`<strong class="code_str" style="margin-left:10px;margin-right:10px;"></strong>`).append($("<input type='text' class='form-control input-sm' style='background-color:#fff;' readonly required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                }
                dom_ul.attr("data-loaded",true);
            }
            else{
                if (row.name == "") {
                    if (permissionUpdate == 1) {
                        $('<li class="j-expend"></li>').append('<label class="fa fa-plus-square-o p-xs"></label>').append(`<strong class="code_str" style="margin-left:10px;margin-right:10px;"></strong>`).append($("<input type='text' class='form-control input-sm' required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                    } else {
                        $('<li class="j-expend"></li>').append('<label class="fa fa-plus-square-o p-xs"></label>').append(`<strong class="code_str" style="margin-left:10px;margin-right:10px;"></strong>`).append($("<input type='text' class='form-control input-sm' style='background-color:#fff;' readonly required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                    }
                    dom_ul.find('input').focus();
                } else {
                    let str_code = row.str_code != '' ? `<strong class="code_str" style="margin-left:10px;margin-right:10px;">${row.str_code}</strong>` : '';
                    if (permissionUpdate == 1) {
                        $('<li class="j-expend"></li>').append('<label class="fa fa-plus-square-o p-xs"></label>').append(str_code).append($("<input type='text' class='form-control input-sm' required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                    } else {
                        $('<li class="j-expend"></li>').append('<label class="fa fa-plus-square-o p-xs"></label>').append(str_code).append($("<input type='text' class='form-control input-sm' style='background-color:#fff;' readonly required/>").attr("data-oldval",row['name']).val(row['name']).attr("name","name")).attr('data-id',row.id).append(extra_info).appendTo(dom_ul);
                    }
                }
            }

            if (permissionDelete == 1) {
                dom_ul.append($("<li class='taxonomy_remove_btn'><button class='toolstip rmv-btn j-remove'><i class='fa fa-remove' title='Remove'></i></button></li>"));
            } 
            if (permissionUpdate == 1) { 
                dom_ul.append($("<li class='taxonomy_edit_btn'><button class='toolstip edit-btn j-edit'><i class='fa fa-edit' title='Edit'></i></button></li>"));  
            }
            if(settings.nodeaddEnable){
                if(curLevel-0>=settings.maxlevel){
                    $("<li></li>").attr("data-id",row.id).appendTo(dom_ul);
                }
                else{
                    $("<li class='taxonomy_addchild_btn'></li>").append($('<button class="btn btn-revelation-primary btn-sm j-addChild"><i class="fa fa-plus"></i>'+language.addchild +'</button>').attr("data-id",row.id)).appendTo(dom_ul);
                }

            }
            for(var j=0;j<settings.extfield.length;j++){
                    var colrender = settings.extfield[j];
                    var coltemplate = generateColumn(row,colrender);
                    $('<li></li>').attr("data-id",row.id).html(coltemplate).appendTo(dom_ul);
            }
            dom_row.append(dom_ul);
            if(isPrepend){
                curElement.prepend(dom_row);
            }
            else{
                curElement.append(dom_row);
            }

        }
	}
})(jQuery)
