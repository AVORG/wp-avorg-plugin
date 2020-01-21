import molecule_mediaObject from "../mediaObject";

interface Recording {
    id: number;
    title: string;
    url: string;
    presenters: {
        photo: string;
        name: string;
    }[];
    presentersString: string;
    videoFiles: {}[];
}

const itemTemplate = function (recording: Recording) {
    const imageUrl = recording.presenters[0] ? recording.presenters[0].photo : null;
    const imageAlt = recording.presenters[0] ? recording.presenters[0].name : null;

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