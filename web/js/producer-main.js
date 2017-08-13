$(document).ready(function(){

    console.log('Here we go!');

    getAlbums();
});

function getAlbums()
{
    $.get('/api/albums', function(albums) {
        albums.map(appendAlbum);
    });
}

function appendAlbum(album)
{
    console.log(album);
    album.artists_text = "No artists added yet";

    var artists = [];
    if (album.artists && album.artists.length) {
        artists = album.artists.map(
            function(artist) {
                return artist.name +" "+artist.instrument;
            }
        );
        album.artists_text = artists.join(', ');
    }

    tmpl = $('#js_album_tpl').html();
    console.log(tmpl);
    $.tmpl( "<tr>"+tmpl+"</tr>", album).appendTo( "#albums" );
}