import molecule_recordingList from "../../molecule/recordingList/index.js";

declare const avorg_scripts: {
    query: any;
    urls: string[]
};

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

export const loadRecordings = (className: string) => {
    const entityId = avorg_scripts.query.entity_id;

    if (!entityId) return;

    const url = `/api/presentation/related/${entityId}`;
    fetch(url).then(response => {
        return response.json();
    }).then(response => {
        const elements = document.querySelectorAll(`.${className}`),
            recordings = getRandomSubarray(Object.values(response), 3),
            content = molecule_recordingList(recordings)

        elements.forEach(el => el.innerHTML = content);
    });
};