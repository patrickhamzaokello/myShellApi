<?php

class Handler
{

    private $ImageBasepath = "https://mwonyaa.com/";
    public $pageNO;
    private $conn;
    private $version;
    public $user_id;
    public $update_date;

    // track update info

    public function __construct($con)
    {
        $this->conn = $con;
        $this->version = 1;
    }



    function allCombined(): array
    {

        $home_page = (isset($_GET['page']) && $_GET['page']) ? htmlspecialchars(strip_tags($_GET["page"])) : '1';

        $page = floatval($home_page);
        $no_of_records_per_page = 10;
        $offset = ($page - 1) * $no_of_records_per_page;

        $sql = "SELECT DISTINCT(genre) as count FROM songs WHERE tag IN ('music') ORDER BY `songs`.`plays` DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $total_rows = floatval($data['count']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $category_ids = array();
        $menuCategory = array();
        $itemRecords = array();


        if ($page == 1) {

            // get_Slider_banner
            $slider_id = array();
            $sliders = array();


            $slider_query = "SELECT id FROM playlist_sliders WHERE status=1 ORDER BY date_created DESC LIMIT 8";
            $slider_query_id_result = mysqli_query($this->conn, $slider_query);
            while ($row = mysqli_fetch_array($slider_query_id_result)) {
                array_push($slider_id, $row['id']);
            }


            foreach ($slider_id as $row) {
                $temp = array();
                $slider = new PlaylistSlider($this->conn, $row);
                $temp['id'] = $slider->getId();
                $temp['playlistID'] = $slider->getPlaylistID();
                $temp['imagepath'] = $slider->getImagepath();
                array_push($sliders, $temp);
            }

            $slider_temps = array();
            $slider_temps['heading'] = "Discover";
            $slider_temps['featured_sliderBanners'] = $sliders;
            array_push($menuCategory, $slider_temps);
            // end get_Slider_banner


            // recently played array
            $recently_played = array();
            $recently_played['heading'] = "Recently Played";
            $recently_played['subheading'] = "Tracks Last Listened to";
            array_push($menuCategory, $recently_played);


            //get genres
            $top_home_genreIDs = array();
            $featured_genres = array();
            $top_genre_stmt = "SELECT DISTINCT(genre) FROM songs WHERE tag IN ('music') ORDER BY `songs`.`plays` DESC LIMIT 8;";
            $top_genre_stmt_result = mysqli_query($this->conn, $top_genre_stmt);

            while ($row = mysqli_fetch_array($top_genre_stmt_result)) {
                array_push($top_home_genreIDs, $row['genre']);
            }

            foreach ($top_home_genreIDs as $row) {
                $genre = new Genre($this->conn, $row);
                $temp = array();
                $temp['id'] = $genre->getGenreid();
                $temp['name'] = $genre->getGenre();
                $temp['tag'] = $genre->getTag();
                array_push($featured_genres, $temp);
            }

            $feat_genres = array();
            $feat_genres['heading'] = "Featured genres";
            $feat_genres['featuredGenres'] = $featured_genres;
            array_push($menuCategory, $feat_genres);

            // end genres


            //get Trending Artist

            $featuredartists = array();
            $featuredCategory = array();

            $musicartistQuery = "SELECT id, profilephoto, name FROM artists WHERE tag='music' ORDER BY overalplays DESC LIMIT 8";
            $feat_cat_id_result = mysqli_query($this->conn, $musicartistQuery);
            while ($row = mysqli_fetch_array($feat_cat_id_result)) {
                array_push($featuredartists, $row);
            }


            foreach ($featuredartists as $row) {
                $temp = array();
                $temp['id'] = $row['id'];
                $temp['profilephoto'] = $row['profilephoto'];
                $temp['name'] = $row['name'];
                array_push($featuredCategory, $temp);
            }

            $feat_Cat_temps = array();
            $feat_Cat_temps['heading'] = "Featured Artists";
            $feat_Cat_temps['featuredArtists'] = $featuredCategory;
            array_push($menuCategory, $feat_Cat_temps);
            ///end featuredArtist
            //get latest Release 14 days
            $featured_albums = array();
            $featuredAlbums = array();

            $featured_album_Query = "SELECT id FROM albums WHERE datecreated > DATE_SUB(NOW(), INTERVAL 14 DAY) ORDER BY `albums`.`datecreated` DESC LIMIT  8";
            $featured_album_Query_result = mysqli_query($this->conn, $featured_album_Query);
            while ($row = mysqli_fetch_array($featured_album_Query_result)) {
                array_push($featured_albums, $row['id']);
            }

            foreach ($featured_albums as $row) {
                $al = new Album($this->conn, $row);
                $temp = array();
                $temp['id'] = $al->getId();
                $temp['heading'] = "New Release From";
                $temp['title'] = $al->getTitle();
                $temp['artworkPath'] = $al->getArtworkPath();
                $temp['tag'] = $al->getTag();
                $temp['artistId'] = $al->getArtistId();
                $temp['artist'] = $al->getArtist()->getName();
                $temp['artistArtwork'] = $al->getArtist()->getProfilePath();
                $temp['Tracks'] = $al->getTracks();
                array_push($featuredAlbums, $temp);
            }

            $feat_albums_temps = array();
            $feat_albums_temps['heading'] = "Latest Release Albums";
            $feat_albums_temps['HomeRelease'] = $featuredAlbums;
            array_push($menuCategory, $feat_albums_temps);
            ///end latest Release 14 days


            //get Featured Playlist
            $featured_playlist = array();
            $featuredPlaylist = array();

            $featured_playlist_Query = "SELECT id,name, owner, coverurl FROM playlists where status = 1 AND featuredplaylist ='yes' ORDER BY RAND () LIMIT 8";
            $featured_playlist_Query_result = mysqli_query($this->conn, $featured_playlist_Query);
            while ($row = mysqli_fetch_array($featured_playlist_Query_result)) {
                array_push($featured_playlist, $row);
            }


            foreach ($featured_playlist as $row) {
                $temp = array();
                $temp['id'] = $row['id'];
                $temp['name'] = $row['name'];
                $temp['owner'] = $row['owner'];
                $temp['coverurl'] = $row['coverurl'];
                array_push($featuredPlaylist, $temp);
            }

            $feat_playlist_temps = array();
            $feat_playlist_temps['heading'] = "Featured Playlists";
            $feat_playlist_temps['featuredPlaylists'] = $featuredPlaylist;
            array_push($menuCategory, $feat_playlist_temps);
            ///end featuredPlaylist


            //get featured Album
            $featured_albums = array();
            $featuredAlbums = array();

            $featured_album_Query = "SELECT * FROM albums WHERE tag = \"music\" ORDER BY totalsongplays DESC LIMIT  8";
            $featured_album_Query_result = mysqli_query($this->conn, $featured_album_Query);
            while ($row = mysqli_fetch_array($featured_album_Query_result)) {
                array_push($featured_albums, $row);
            }


            foreach ($featured_albums as $row) {
                $temp = array();
                $temp['id'] = $row['id'];
                $temp['title'] = $row['title'];
                $temp['artworkPath'] = $row['artworkPath'];
                $temp['tag'] = $row['tag'];
                array_push($featuredAlbums, $temp);
            }

            $feat_albums_temps = array();
            $feat_albums_temps['heading'] = "Featured Albums";
            $feat_albums_temps['featuredAlbums'] = $featuredAlbums;
            array_push($menuCategory, $feat_albums_temps);
            ///end featuredAlbums


            //get featured Dj mixes
            $featured_dj_mixes = array();
            $featuredDJMIXES = array();

            $featured_mixes_Query = "SELECT * FROM albums WHERE tag = \"dj\" ORDER BY datecreated DESC LIMIT 8";
            $featured_mixes_Query_result = mysqli_query($this->conn, $featured_mixes_Query);
            while ($row = mysqli_fetch_array($featured_mixes_Query_result)) {
                array_push($featured_dj_mixes, $row);
            }


            foreach ($featured_dj_mixes as $row) {
                $temp = array();
                $temp['id'] = $row['id'];
                $temp['title'] = $row['title'];
                $temp['artworkPath'] = $row['artworkPath'];
                $temp['tag'] = $row['tag'];
                array_push($featuredDJMIXES, $temp);
            }

            $feat_albums_temps = array();
            $feat_albums_temps['heading'] = "Featured Mixes";
            $feat_albums_temps['FeaturedDjMixes'] = $featuredDJMIXES;
            array_push($menuCategory, $feat_albums_temps);
            ///end featuredAlbums


        }


        //fetch other categories Begin
        $home_genreIDs = array();
        $genre_stmt = "SELECT DISTINCT(genre) FROM songs WHERE tag IN ('music') ORDER BY `songs`.`plays` DESC LIMIT " . $offset . "," . $no_of_records_per_page . "";
        $genre_stmt_result = mysqli_query($this->conn, $genre_stmt);

        while ($row = mysqli_fetch_array($genre_stmt_result)) {

            array_push($home_genreIDs, $row['genre']);
        }

        foreach ($home_genreIDs as $row) {
            $genre = new Genre($this->conn, $row);
            $temp = array();
            $temp['id'] = $genre->getGenreid();
            $temp['name'] = $genre->getGenre();
            $temp['tag'] = $genre->getTag();
            $temp['Tracks'] = $genre->getGenre_Songs(6);
            array_push($menuCategory, $temp);
        }

        $itemRecords["version"] = $this->version;
        $itemRecords["page"] = $page;
        $itemRecords["featured"] = $menuCategory;
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;

        return $itemRecords;
    }




    function EventsHome(): array
    {

        $event_page = (isset($_GET['page']) && $_GET['page']) ? htmlspecialchars(strip_tags($_GET["page"])) : '1';

        $page = floatval($event_page);
        $no_of_records_per_page = 10;
        $offset = ($page - 1) * $no_of_records_per_page;
        $date_now = date('Y-m-d');


        $sql = "SELECT COUNT(id) as count FROM events WHERE (endDate >= '$date_now') AND featured = '1' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $total_rows = floatval($data['count']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $category_ids = array();
        $menuCategory = array();
        $itemRecords = array();


        if ($page == 1) {

            $event_ids = array();
            $today_s_event = array();
            $today_s_event_stmt = "SELECT id FROM events  WHERE (endDate >= '$date_now') AND featured = 1  ORDER BY `events`.`ranking` DESC LIMIT 8";
            $today_s_event_stmt_result = mysqli_query($this->conn, $today_s_event_stmt);

            while ($row = mysqli_fetch_array($today_s_event_stmt_result)) {

                array_push($event_ids, $row['id']);
            }

            foreach ($event_ids as $row) {
                $event = new Events($this->conn, $row);
                $temp = array();
                $temp['id'] = $event->getId();
                $temp['title'] = $event->getTitle();
                $temp['description'] = $event->getDescription();
                $temp['startDate'] = $event->getStartDate();
                $temp['startTime'] = $event->getStartTime();
                $temp['endDate'] = $event->getEndDate();
                $temp['endtime'] = $event->getEndtime();
                $temp['location'] = $event->getLocation();
                $temp['host_name'] = $event->getHostName();
                $temp['host_contact'] = $event->getHostContact();
                $temp['image'] = $event->getImage();
                $temp['ranking'] = $event->getRanking();
                $temp['featured'] = $event->getFeatured();
                $temp['date_created'] = $event->getDateCreated();
                array_push($today_s_event, $temp);
            }


            $podcast_temps = array();
            $podcast_temps['heading'] = "Events";
            $podcast_temps['subheading'] = "This is where you Happen! find out more and contact the hosts directly";
            $podcast_temps['TodayEvents'] = $today_s_event;
            array_push($menuCategory, $podcast_temps);
            // end get_Slider_banner


            // get_Slider_banner
            $slider_id = array();
            $sliders = array();


            $slider_query = "SELECT id FROM search_slider WHERE status=1 ORDER BY date_created DESC LIMIT 8";
            $slider_query_id_result = mysqli_query($this->conn, $slider_query);
            while ($row = mysqli_fetch_array($slider_query_id_result)) {
                array_push($slider_id, $row['id']);
            }


            foreach ($slider_id as $row) {
                $temp = array();
                $slider = new SearchSlider($this->conn, $row);
                $temp['id'] = $slider->getId();
                $temp['playlistID'] = $slider->getPlaylistID();
                $temp['imagepath'] = $slider->getImagepath();
                array_push($sliders, $temp);
            }

            $slider_temps = array();
            $slider_temps['heading'] = "Discover Exclusive Shows on Mwonyaa";
            $slider_temps['podcast_sliders'] = $sliders;
            array_push($menuCategory, $slider_temps);
            // end get_Slider_banner


        }


        //get featured Album
        $other_events = array();

        $other_events_Query = "SELECT id FROM events  WHERE (endDate >= '$date_now') AND featured = 1 ORDER BY `events`.`ranking` DESC LIMIT " . $offset . "," . $no_of_records_per_page . "";

        $other_events_Query_result = mysqli_query($this->conn, $other_events_Query);
        while ($row = mysqli_fetch_array($other_events_Query_result)) {
            array_push($other_events, $row['id']);
        }

        foreach ($other_events as $row) {
            $event = new Events($this->conn, $row);
            $temp = array();
            $temp['id'] = $event->getId();
            $temp['title'] = $event->getTitle();
            $temp['description'] = $event->getDescription();
            $temp['startDate'] = $event->getStartDate();
            $temp['startTime'] = $event->getStartTime();
            $temp['endDate'] = $event->getEndDate();
            $temp['endtime'] = $event->getEndtime();
            $temp['location'] = $event->getLocation();
            $temp['host_name'] = $event->getHostName();
            $temp['host_contact'] = $event->getHostContact();
            $temp['image'] = $event->getImage();
            $temp['ranking'] = $event->getRanking();
            $temp['featured'] = $event->getFeatured();
            $temp['date_created'] = $event->getDateCreated();
            array_push($menuCategory, $temp);
        }


        $itemRecords["version"] = $this->version;
        $itemRecords["page"] = $page;
        $itemRecords["EventsHome"] = $menuCategory;
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;

        return $itemRecords;
    }


    function SelectedEvents(): array
    {

        $event_page = (isset($_GET['page']) && $_GET['page']) ? htmlspecialchars(strip_tags($_GET["page"])) : '1';
        $event_id = (isset($_GET['eventID']) && $_GET['eventID']) ? htmlspecialchars(strip_tags($_GET["eventID"])) : '1';

        $page = floatval($event_page);
        $no_of_records_per_page = 10;
        $offset = ($page - 1) * $no_of_records_per_page;
        $date_now = date('Y-m-d');

        $sql = "SELECT COUNT(id) as count FROM events WHERE id != $event_id AND (endDate >= '$date_now') AND featured = '1' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $total_rows = floatval($data['count']);
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $menuCategory = array();
        $itemRecords = array();


        if ($page == 1) {
            $event = new Events($this->conn, $event_id);
            $temp = array();
            $temp['id'] = $event->getId();
            $temp['title'] = $event->getTitle();
            $temp['description'] = $event->getDescription();
            $temp['startDate'] = $event->getStartDate();
            $temp['startTime'] = $event->getStartTime();
            $temp['endDate'] = $event->getEndDate();
            $temp['endtime'] = $event->getEndtime();
            $temp['location'] = $event->getLocation();
            $temp['host_name'] = $event->getHostName();
            $temp['host_contact'] = $event->getHostContact();
            $temp['image'] = $event->getImage();
            $temp['ranking'] = $event->getRanking();
            $temp['featured'] = $event->getFeatured();
            $temp['date_created'] = $event->getDateCreated();
            array_push($menuCategory, $temp);
            // end selected event

        }


        //get featured Album
        $other_events = array();

        $other_events_Query = "SELECT id FROM events  WHERE id != $event_id AND (endDate >= '$date_now') AND featured = 1 ORDER BY `events`.`ranking` DESC LIMIT " . $offset . "," . $no_of_records_per_page . "";

        $other_events_Query_result = mysqli_query($this->conn, $other_events_Query);
        while ($row = mysqli_fetch_array($other_events_Query_result)) {
            array_push($other_events, $row['id']);
        }

        foreach ($other_events as $row) {
            $event = new Events($this->conn, $row);
            $temp = array();
            $temp['id'] = $event->getId();
            $temp['title'] = $event->getTitle();
            $temp['description'] = $event->getDescription();
            $temp['startDate'] = $event->getStartDate();
            $temp['startTime'] = $event->getStartTime();
            $temp['endDate'] = $event->getEndDate();
            $temp['endtime'] = $event->getEndtime();
            $temp['location'] = $event->getLocation();
            $temp['host_name'] = $event->getHostName();
            $temp['host_contact'] = $event->getHostContact();
            $temp['image'] = $event->getImage();
            $temp['ranking'] = $event->getRanking();
            $temp['featured'] = $event->getFeatured();
            $temp['date_created'] = $event->getDateCreated();
            array_push($menuCategory, $temp);
        }


        $itemRecords["version"] = $this->version;
        $itemRecords["page"] = $page;
        $itemRecords["Events"] = $menuCategory;
        $itemRecords["total_pages"] = $total_pages;
        $itemRecords["total_results"] = $total_rows;

        return $itemRecords;
    }


}
