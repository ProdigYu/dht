
(function(){
	function search_request(){
		// is scrolling
		if (window.scrolling){
			return false;
		}
		var q = $('#input-q').val();
		var p = $('#input-p').val();
		var has_more = $('#input-has-more').val();

		if (!q){
			return false;
		}

		// nothing more
		if (!has_more || parseInt(has_more) == 0){
			return false;
		}

		window.scrolling = true;

		var loading_html = '<div class="search-row loading">';
		loading_html += '<i class="fa fa-spin fa-cog fa-3x fa-fw"></i>';
		loading_html += '</div>';
		$('.search-result').append(loading_html);

		$.ajax({
			'url': '/search',
			'type': 'post',
			'dataType': 'json',
			'data': {'q': q, 'p': parseInt(p)+1},
			success: function(json){
				$('#input-p').val(json.p);
				$('#input-has-more').val(json.has_more?1:0);
				var source = $('#search-row-handlebars').html();
				var template = Handlebars.compile(source);
				var html = template(json);
				$('.search-result').append(html);
			},
			complete: function(){
				window.scrolling = false;
				$('.loading').remove();
			}
		});
	}

	function click_search_result(event){
		event.preventDefault();
		$('#search-body').find('.search-detail').remove();

		var $target = $(event.target);
		var $parent = $target.parents('.search-row');
		var hash = $parent.attr('hash');
		$.ajax({
			'url': '/detail',
			'type': 'post',
			'dataType': 'json',
			'data': {'hash': hash},
			'success': function(json){
				var source = $('#search-detail-handlebars').html();
				var template = Handlebars.compile(source);
				var html = template({'name': json.infohash.name, 'filelist': json.filelist});
				$('#search-body').append('<div class="search-detail">' + html + '</div>');
			}
		});
	}

	var scrolling = false;
	var ngApp = angular.module('ngApp', []);
	ngApp.controller('searchController', function($scope){
		$scope.name = 'init name';
		$scope.filelist = Array();
	});
	$(function(){
		$(window).scroll(function(){
			if ($(window).height() + $(window).scrollTop() + 200 >= $(document).height() ){
				search_request();
			}
		});
		if ($.support.pjax){
			$(document).pjax('.name-line a', '#main');
		} else {
			$(document).on('click', '.name-line a', function(event) {
				click_search_result(event);
			});
		}

		$(document).on('click', 'body', function(event){
			event.stopPropagation();
			var $target = $(event.target);
			if ($target.parents('.search-detail').length <= 0 && 
				!$target.hasClass('search-detail')){
				$('.search-detail').remove();
			}
		});

	});
	// Nprogress
	$(document).on('pjax:start', function(){NProgress.start();});
	$(document).on('pjax:end', function(){NProgress.done();});
	$(document).ready(function(){NProgress.start(); });
	$(window).load(function(){NProgress.done(); });
})();
