namespace AvorgMoleculeSearch {
    function Search() {
        const endpoints = [
                "/wp-json/avorg/v1/presenters",
                "/wp-json/avorg/v1/conferences",
            ],
            [query, setQuery] = wp.element.useState(''),
            [suggestions, setSuggestions] = wp.element.useState([]),
            [searchTimeout, setSearchTimeout] = wp.element.useState();

        console.log({'suggestions': suggestions});

        function onQueryChange(e: any) {
            setQuery(e.target.value);

            clearTimeout(searchTimeout);

            setSuggestions([]);

            setSearchTimeout(setTimeout(loadSuggestions, 200, e.target.value))
        }

        function loadSuggestions(input: string)
        {
            endpoints.forEach((endpoint) => {
                const url = `${endpoint}?search=${input}`;
                console.log(url);
                fetch(url)
                    .then(res => res.json())
                    .then((data) => setSuggestions((prev: []) => [...prev, ...data]))
            })
        }

        return <form action={`/${window.avorg.query.language}/search`} method={'GET'}>
            <input
                name={'q'}
                type="text"
                placeholder={'Search'}
                value={query}
                onChange={onQueryChange}
            />
            <input type="submit" value={'Go'}/>
            <ul>
                {suggestions.map((item: any, i: number) => <li key={i}>
                    <a href={item.url}>{item.name || item.title}</a>
                </li>)}
            </ul>
        </form>
    }

    const components = document.querySelectorAll('.avorg-molecule-search');

    components.forEach((el) => {
        window.wp.element.render(<Search/>, el);
    })
}