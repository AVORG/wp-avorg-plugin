import molecule_mediaObject from "../mediaObject/index.js";

interface Recording {
    id: number;
    title: string;
    url: string;
    presenters: {
        photo: string;
        name: {
            first: string;
            last: string;
            suffix: string;
        };
    }[];
    presentersString: string;
    videoFiles: {}[];
}

const itemTemplate = function (recording: Recording) {
    const imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
    const imageAlt = recording.presenters[0] ?
        `${recording.presenters[0].name.first} ${recording.presenters[0].name.last} ${recording.presenters[0].name.suffix}` : null;

    return molecule_mediaObject(
        recording.title,
        recording.url,
        recording.presentersString,
        imageUrl,
        imageAlt
    );
};

const molecule_recordingList = (recordings: Recording[]) => {
    return recordings.map(itemTemplate).join("");
};

export default molecule_recordingList