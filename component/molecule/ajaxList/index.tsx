import molecule_mediaObject from '../mediaObject';
import * as React from 'react';
import {RefObject} from 'react';

namespace AvorgMoleculeAjaxList {
    interface DataObject {
        id: string
        title: string
        secondLine?: string
        url: string
        photo256: string
    }

    interface AjaxListProps {
        endpoint: string,
        search: string
    }

    interface AjaxListState {
        entries: DataObject[]
        isLoading: boolean
        page: number
        resultsExhausted: boolean
        searchTimeout: number
        search: string
    }

    class AjaxList extends React.Component<AjaxListProps, AjaxListState> {

        wrapperRef: RefObject<HTMLDivElement>;

        constructor(props: AjaxListProps) {
            super(props);

            this.state = {
                entries: [],
                isLoading: true,
                page: 0,
                resultsExhausted: false,
                searchTimeout: null,
                search: this.props.search
            };

            this.wrapperRef = React.createRef();

            this.setSearch = this.setSearch.bind(this);
        }

        componentDidMount(): void {
            this.loadEntries();

            window.addEventListener("scroll", () => {
                if (this.shouldLoadEntries()) {
                    this.loadEntries()
                }
            });
        }

        shouldLoadEntries() {
            return !this.state.isLoading
                && !this.state.resultsExhausted
                && this.isEndVisible();
        }

        setSearch(e: any) {
            const target = e.target as HTMLInputElement;

            clearTimeout(this.state.searchTimeout);

            this.setState({
                search: target.value,
                searchTimeout: setTimeout(() => {
                    this.resetEntries();
                    this.loadEntries();
                }, 1200)
            } as AjaxListState);
        }

        resetEntries() {
            this.setState({
                entries: [],
                page: 0,
                resultsExhausted: false
            } as AjaxListState)
        }

        loadEntries() {
            this.setState((prev) => ({
                isLoading: true
            }));

            const url = `${this.props.endpoint}?start=${this.state.page * 25}&search=${this.state.search}`;

            fetch(url)
                .then(res => res.json())
                .then((data) => {
                    this.setState((prev) => ({
                        entries: prev.entries.concat(data),
                        isLoading: false,
                        page: prev.page + 1,
                        resultsExhausted: data.length === 0
                    }));
                });
        }

        isEndVisible(): boolean {
            const rect = this.wrapperRef.current.getBoundingClientRect();

            return rect.bottom <= window.innerHeight;
        }

        render() {
            return (
                <div ref={this.wrapperRef}>
                    <input
                        type="text"
                        placeholder={'Search'}
                        value={this.state.search}
                        onChange={this.setSearch}
                    />
                    <ul className={this.state.isLoading ? "loading" : ""}>
                        {this.state.entries.map(
                            (entry: DataObject, i: number) =>
                                <li key={i}>
                                    {
                                        molecule_mediaObject(
                                            entry.title,
                                            entry.url,
                                            entry.secondLine,
                                            entry.photo256,
                                            entry.title
                                        )
                                    }
                                </li>
                        )}
                    </ul>
                </div>
            );
        }
    }

    const components = document.querySelectorAll('.avorg-molecule-ajaxList');

    components.forEach((el) => {
        const endpoint = el.getAttribute('data-endpoint'),
            search = el.getAttribute('data-search');

        window.wp.element.render(<AjaxList endpoint={endpoint} search={search} />, el);
    });
}