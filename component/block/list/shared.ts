import molecule_recordingList from "../../molecule/recordingList/index.js";

export const loadRecordings = (className: string) => {
    const elements = document.querySelectorAll(`.${className}`);

    elements.forEach(el => {
        const list = el.getAttribute('data-type'),
            url = `http://localhost:8000/api/presentation/${list}`;
        fetch(url).then(response => {
            return response.json();
        }).then(response => {
            el.innerHTML = molecule_recordingList(response);
        });
    });
};
