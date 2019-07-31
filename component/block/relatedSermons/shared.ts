import molecule_mediaObject from "../../molecule/mediaObject/index.js";

declare const avorg_scripts: {
    query: any;
    urls: string[]
};

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

function getRandomSubarray(arr: any[], size: number) {
    var shuffled = arr.slice(0), i = arr.length, temp, index;
    while (i--) {
        index = Math.floor((i + 1) * Math.random());
        temp = shuffled[index];
        shuffled[index] = shuffled[i];
        shuffled[i] = temp;
    }
    return shuffled.slice(0, size);
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

export const loadRecordings = (className: string) => {
    const url = `http://localhost:8000/api/related/${avorg_scripts.query.entity_id}`;
    fetch(url).then(response => {
        return response.json();
    }).then(response => {
        const elements = document.querySelectorAll(`.${className}`),
            recordings = getRandomSubarray(Object.values(response), 3),
            content = recordings.map(itemTemplate).join("");

        elements.forEach(el => el.innerHTML = content);
    });
};