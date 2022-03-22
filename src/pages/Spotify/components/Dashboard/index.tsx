import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useSearchParams } from "react-router-dom";
import { Reducers } from "../../../../redux/types";
import { getSpotifySearch } from "../../../../redux/spotify";
import { useSpotify } from "../../../../components/SpotifyPlayer";

interface TrackProps {
  artists: { name: string }[];
  name: string;
  uri: string;
  album: { images: { height: string; url: string }[] };
}

const Dashboard: React.FC = () => {
  const { setMusic } = useSpotify();
  const dispatch = useDispatch();
  const [searchParams, setSearchParams] = useSearchParams();
  const { spotify } = useSelector((state: Reducers) => state);

  const code = searchParams.get("code");
  useEffect(() => {
    if (code && !spotify.auth.isLoading && spotify.auth.data) {
      setSearchParams({});
    }
  }, [code, setSearchParams, spotify.auth.data, spotify.auth.isLoading]);

  const onMapTrack = (item: TrackProps) => {
    const smallestAlbumImage = item.album.images.reduce((smallest, image) => {
      if (image.height < smallest.height) return image;
      return smallest;
    }, item.album.images[0]);

    return {
      artist: item.artists[0].name,
      name: item.name,
      uri: item.uri,
      albumUrl: smallestAlbumImage ? smallestAlbumImage.url : null,
    };
  };

  return (
    <>
      <div className="columns">
        <div className="column">
          <input
            className="input"
            type="text"
            placeholder="Search Songs/Artists"
            onChange={(e) =>
              dispatch(getSpotifySearch({ q: e.currentTarget.value }))
            }
          />
        </div>
      </div>
      <div className="columns">
        <div
          className="column"
          style={{ height: "calc(100vh - 300px)", overflowY: "auto" }}
        >
          {spotify.search.isLoading && "Loading"}
          {!spotify.search.isLoading &&
            spotify.search.data &&
            (spotify.search.data.tracks.items as TrackProps[])
              .map(onMapTrack)
              .map((item, i) => (
                <article className="media" key={`spotify-search-${i + 1}`}>
                  <figure className="media-left">
                    <p
                      className="image is-64x64"
                      onClick={() => setMusic(item.uri)}
                      style={{ cursor: "pointer" }}
                      title="Play"
                    >
                      {item.albumUrl && <img src={item.albumUrl} />}
                    </p>
                  </figure>
                  <div className="media-content">
                    <div className="content">
                      <p>
                        <span>{item.name}</span>
                        <br />
                        <span className="has-text-grey-light">
                          {item.artist}
                        </span>
                      </p>
                    </div>
                  </div>
                </article>
              ))}
        </div>
      </div>
    </>
  );
};

export default Dashboard;
