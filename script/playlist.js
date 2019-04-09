console.log("avorg", avorg);

const Player = {
    player: null,
    recording: null,
    showingVideo: null,
    endHandler: null,

    hasVideo: function() {
        return this.recording.videoFiles.length > 0;
    },

    template: function (playerId, sources) {
        return `
        <video id="${playerId}" class="avorg-prototype-player video-js vjs-fluid" controls preload="auto"
            poster="https://s.audioverse.org/images/template/player-bg4.jpg">
            
            ${sources.map((source) => `<source src="${source.streamUrl}" type="${source.type}">`).join("")}
            
            <p class="vjs-no-js">
                To view this media please enable JavaScript, and consider upgrading to a web browser that
                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
            </p>
        </video>
        
        ${this.hasVideo() ? 
            "<button id=\"avorg-source-toggle\" class=\"avorg-prototype-player__toggle\">Toggle Source</button>" : ""}
        `;
    },

    setClickHandlers: function() {
        if (!this.hasVideo()) return;

        document.getElementById("avorg-source-toggle").addEventListener("click", (e) => {
            this.showingVideo = !this.showingVideo;
            this.load();
        });
    },

    init: function(recording) {
        this.recording = recording;
        this.showingVideo = this.hasVideo();
    },

    log: function() {
        if (! this.recording.logUrl) { return; }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', this.recording.logUrl, true);
        xhr.send();
    },

    load: function (recording = this.recording, endHandler = null) {
        this.endHandler = endHandler;

        if (recording !== this.recording) this.init(recording);

        if (this.player !== null) this.player.dispose();

        const playerId = `player_${this.recording.id}_${this.showingVideo ? "video" : "audio"}`,
            sources = this.showingVideo ? this.recording.videoFiles : this.recording.audioFiles;

        document.getElementsByClassName("avorg-page-playlist__player")[0].innerHTML = this.template(playerId, sources);

        this.setClickHandlers();

        this.player = videojs(playerId);
        this.player.on("ended", this.endHandler);

        this.log()
    }
};

const Playlist = {
    player: null,
    recordings: null,
    index: 0,

    listItemTemplate: function (recording) {
        return `
        <li data-id="${recording.id}">
        ${recording.title} ${recording.videoFiles.length ? "(video)" : ""}<br/>
        ${recording.presenters.map((presenter) => `${presenter.name.first} ${presenter.name.last} ${presenter.name.suffix}`).join(", ")}
        </li>
        `;
    },

    renderList: function() {
        const listHtml = this.recordings.map(this.listItemTemplate).join("");
        document.getElementsByClassName("avorg-page-playlist__list")[0].innerHTML = listHtml;
    },

    registerClickHandler: function() {
        document.querySelectorAll(".avorg-page-playlist__list li").forEach((item) => {
            item.addEventListener("click", (e) => {
                const id = e.target.getAttribute("data-id");

                Player.load(avorg.recordings[id]);
            }, false)
        });
    },

    loadRecordingAtIndex: function(i) {
        const recording = this.recordings[i];
        this.index = i;

        this.player.load(recording, this.next.bind(this));

        document.querySelectorAll(".avorg-page-playlist__list li")
            .forEach((e) => {e.classList.remove("active")});
        document.querySelector(`.avorg-page-playlist__list li[data-id="${recording.id}"]`).classList.add("active");
    },

    next: function() {
        this.loadRecordingAtIndex(this.index + 1);
    },

    init: function ( player, recordings) {
        this.player = player;
        this.recordings = recordings;

        this.renderList();
        this.registerClickHandler();
        this.loadRecordingAtIndex(0);
    }
};

document.addEventListener("DOMContentLoaded", function (event) {
    const recordingsArray = Object.values(avorg.recordings);

    Playlist.init(Player, recordingsArray);
});
