<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
		<style>
			#results {
				margin-bottom: 2em;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="page-header">
				<h1>Michikokatsu.com</h1> <small>Feed search demo</small>
			</div>
<!-- 			<div class="panel panel-default">
				<div class="panel-body">
					<p>Categories go here</p>
				</div>
			</div> -->
			<form id="search" class="form-horizontal" action="/" role="form">
				<div class="form-group">
					<label for="query" class="col-sm-1 control-label">Query</label>
					<div class="col-sm-10">
						<input id="query" type="text" class="form-control" placeholder="Search terms">
					</div>
					<div class="col-sm-1">
						<button type="submit">Search</button>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-11 col-sm-offset-1">
						Search by:
						<div class="radio">
							<label>
								<input type="radio" name="df" id="optionsRadios1" value="title" checked>
								Title
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="df" id="optionsRadios2" value="author">
								Author
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="df" id="optionsRadios3" value="content">
								Content
							</label>
						</div>
					</div>
				</div>
			</form>
			<div id="results" class="container">				
				<div class="list-group">
					<h3>Results:</h3>
				</div>
			</div>
		</div>
	</body>	
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script>
		$('#search').submit(function(event) {
			event.preventDefault();
			
			var query = $('#search input').val();	
			var defaultSearch = $('#search input:radio[name=df]:checked').val();	

			$.ajax({
				url: 'http://localhost:8983/solr/select/',
				data: {
					defType: 'edismax',
					q: query,
					df: defaultSearch,
					wt: 'json'
				},
				success: function(data) {
					showResults(data);
				},
				dataType: 'jsonp',
				jsonp: 'json.wrf'
			});
		});

		function showResults(data) {
			console.log(data);

			$('#results a').each(function() {
				$(this).remove();
			});

			var markup = "";

			if (data.response.numFound == 0) {
				markup = '<a href="#" target="_blank" class="list-group-item">'
				+ '<p class="list-group-item-text">No results found</p>';
				+ '</a>';
			} else {
				var excerpt = "";

				$.each(data.response.docs, function(i, v) {
					excerpt = "";

					if (typeof(v.description) === 'undefined') {
						if (typeof(v.content) !== 'undefined') {
							excerpt = v.content;
						}
					} else {
						excerpt = v.description;
					}

					if (excerpt !== "") {
						excerpt = '<p class="list-group-item-text">' + stripHTML(excerpt).substring(0, 500) + '</p>';				
					}

					markup += '<a href="' + v.id + '" target="_blank" class="list-group-item">'
						+ '<h4 class="list-group-item-heading">' + v.title + '</h4>'
						+ '<h5>Author: ' + v.author + '</h5>'
						+ excerpt
						+ '</a>';
				});
			}

			$('#results').append(markup);
		}

		function stripHTML(html) {
			var tmp = document.createElement("div");
			tmp.innerHTML = html;
			return tmp.textContent || tmp.innerText || "";
		}

	</script>
</html>
