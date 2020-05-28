import Highlighter from "react-highlight-words";

namespace AvorgMoleculeSearch {
    function Search() {
        const [query, setQuery] = wp.element.useState(''),
            [suggestions, setSuggestions] = wp.element.useState([]),
            [searchTimeout, setSearchTimeout] = wp.element.useState();

        console.log({'suggestions': suggestions});

        function onQueryChange(e: any) {
            setQuery(e.target.value);

            clearTimeout(searchTimeout);

            setSuggestions([]);

            setSearchTimeout(setTimeout(loadSuggestions, 200, e.target.value))
        }

        console.log(query.split(' '));
        console.log(Highlighter);

        function loadSuggestions(input: string)
        {
            fetch(`/wp-json/avorg/v1/suggestions?term=${input}`)
                .then(res => res.json())
                .then((data) => setSuggestions(data));
        }

        return <form className={'avorg-organism-search'} action={`/${window.avorg.query.language}/search`} method={'GET'}>
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
                    <a href={item.url} data-relevance={item.relevance} data-weight={item.weight}>
                        <Highlighter searchWords={query.split(' ')} textToHighlight={item.title} highlightClassName={'avorg-organism-search__highlight'} />
                        <span>{item.type}</span>
                    </a>
                </li>)}
            </ul>
        </form>
    }

    const components = document.querySelectorAll('.avorg-molecule-search');

    components.forEach((el) => {
        window.wp.element.render(<Search/>, el);
    })
}