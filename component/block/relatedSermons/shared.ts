import molecule_recordingList from "../../molecule/recordingList";

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
    const entityId = avorg_scripts.query.entity_id,
        elements = document.querySelectorAll(`.${className}`);

    if (!entityId || !elements) return;

    const url = `/api/presentation/related/${entityId}`;
    fetch(url).then(response => {
        console.log(response);

        return response.json();
    }).then(response => {
        const recordings = getRandomSubarray(Object.values(response), 3),
            content = molecule_recordingList(recordings);

        elements.forEach(el => el.innerHTML = content);
    });
};