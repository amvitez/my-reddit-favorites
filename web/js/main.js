var favorites = [];
var userID;

$(document).ready(function(){

	// Get the userID for this session and store it
	$.ajax({
		type: "POST",
		async: false,
		url: "getUserID.php",
		success: function(data){
			alert(data);
			userID = data;
		}
	});
	
	// Get the favorties for the user and append them to the HTML
	$.ajax({
		type: "POST",
		async: false,
		data: {userID: userID},
		url: "getFavorites.php",
		success: function(data){
			var json = JSON.parse(data);
			loadFavoriteList("#favorite-list ul", json);
		}
	});

	$.getJSON('http://www.reddit.com/hot.json', function(data) {
	  loadRedditList("#hot-list ul", data.data.children);
	});

	$.getJSON('http://www.reddit.com/top.json', function(data) {
	  loadRedditList("#top-list ul", data.data.children);
	});

});

function loadRedditList(list, children){
	var length = children.length;
	
	for(var i = 0; i<length; i++){
		var child = children[i].data;		
		var isFave = false;
		
		// See if any of the posts in the hot or top list are currently one of our favorites. If so, visually indicate that by starring them
		for(var j = 0;!isFave && j < favorites.length; j++){
			isFave = favorites[j] == child.id;
		}
		
		var faveClass = isFave ? 'fave' : 'not-fave';
		var thumbnail = checkThumbnail(child.thumbnail, true);
		$(list).append('<li><div data-id="'+child.id+'" data-title="'+child.title+'" data-url="'+child.url+'" data-thumbnail="'+checkThumbnail(child.thumbnail, false)+'" class="star '+faveClass+'"></div>'+thumbnail+'<a href='+child.url+' target="_blank">'+child.title+'</a><p class="comment-count">'+child.num_comments+' comments</p></li>');
	  }
}

function loadFavoriteList(list, data){	
	favorites = [];
	
	for(var i = 0; i<data.length; i++){
	  var current = data[i];
	  
	  if(current.redditID){
		favorites.push(current.redditID);
		appendFavoriteToList(list, current.redditID, current.title, current.url, current.thumbnail);
	  }
	}
}

function appendFavoriteToList(list, redditID, title, url, thumbnail){
	$(list).append('<li><div data-id="'+redditID+'" class="star fave"></div>'+checkThumbnail(thumbnail, true)+'<a href='+url+' target="_blank">'+title+'</a><button class="remove-favorite-button">remove</button></li>');
}

$('#hot-list, #top-list').on('click', '.star', function(){
	var id = $(this).data("id");
	var title = $(this).data("title");
	var url = $(this).data("url");
	var thumbnail = $(this).data("thumbnail");
	
	// If the user clicks on a post that is not currently a favorite, then add it to their favorites (both in the DB and in the HTML favorites tab)
	if($(this).hasClass('not-fave')){
		$.ajax({
			type: "POST",
			async: true,
			url: "addFavorite.php",
			data: { userID: userID, 
					redditID: id,
					title: title,
					url: url,
					thumbnail: thumbnail},
			success: function(data){
				alert(data);
			}
		});
	
		// Add fave class which will display a golden star next to the post
		$(this).removeClass('not-fave').addClass('fave');
		
		// The same post may be in the other list, so we want to visually update that post as well
		$("#hot-list, #top-list").find("[data-id='" + id + "']").removeClass('not-fave').addClass('fave');
		
		// Add to user's list of favorites
		favorites.push(id);
		appendFavoriteToList("#favorite-list ul", id, title, url, thumbnail);
	}
	else{	
		$(this).removeClass('fave').addClass('not-fave');
		deleteFavorite(id);
	}
});

// Event handler when the user clicks 'remove' on a post in their favorites list
$('#favorite-list').on('click', '.remove-favorite-button', function(){
	deleteFavorite($(this).siblings("div.star").data("id"));
});

// Remove favorite record from DB, remove the post from the favorites array, 
// remove HTML list element from favorites list and remove golden stars from other occurrences from both hot and top lists
function deleteFavorite(redditID){
	$.ajax({
		type: "POST",
		async: true,
		url: "deleteFavorite.php",
		data: { userID: userID, redditID: redditID},
		success: function(data){
			favorites = $.grep(favorites, function(value) {
			  return value != redditID;
			});
			
			$("#favorite-list").find("[data-id='" + redditID + "']").parent().remove();
			$("#hot-list, #top-list").find("[data-id='" + redditID + "']").removeClass('fave').addClass('not-fave');
		}
	});
}

// This function ensures we exclude the 'default', 'self', 'nsfw' and empty thumbnail links
function checkThumbnail(input, createImgElement){
	if (input && input != 'default' && input != 'self' && input != 'nsfw'){
			if(createImgElement)
				return '<img class="thumbnail" alt="thumbnail" src="'+input+'">';
				
		    return input;			
	}
			
	return '';
}

