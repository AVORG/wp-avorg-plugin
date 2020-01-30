namespace AvorgMoleculeSearch {
    function Search() {
        const [query, setQuery] = wp.element.useState('');

        return <form action={`/${window.avorg.query.language}/search`} method={'GET'}>
            <input
                name={'q'}
                type="text"
                placeholder={'Search'}
                value={query}
                onChange={(e) => setQuery(e.target.value)}
            />
            <input type="submit" value={'Go'}/>
        </form>
    }

    const components = document.querySelectorAll('.avorg-molecule-search');

    components.forEach((el) => {
        window.wp.element.render(<Search/>, el);
    })
}