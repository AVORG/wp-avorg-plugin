import molecule_mediaObject from '../mediaObject';
import * as React from 'react';
import {RefObject} from 'react';

namespace AvorgMoleculeAjaxList {
    interface DataObject {
        id: string,
        title: string,
        url: string
        photo256: string
    }

    interface AjaxListProps {
        endpoint: string
    }

    interface AjaxListState {
        entries: DataObject[],
        isLoading: boolean
    }

    class AjaxList extends React.Component<AjaxListProps, AjaxListState> {
        myRef: RefObject<HTMLDivElement>;

        constructor(props: AjaxListProps) {
            super(props);

            this.state = {
                entries: [],
                isLoading: true
            };

            this.myRef = React.createRef();
        }

        componentDidMount(): void {
            this.loadEntries();

            window.addEventListener("scroll", () => {
                if (!this.state.isLoading && this.isEndVisible()) {
                    this.loadEntries()
                }
            });
        }

        loadEntries() {
            this.setState({
                isLoading: true
            });

            fetch(this.props.endpoint)
                .then(res => res.json())
                .then((data) => {
                    this.setState({
                        entries: this.state.entries.concat(data),
                        isLoading: false
                    });
                });
        }

        isEndVisible(): boolean {
            const rect = this.myRef.current.getBoundingClientRect();

            return rect.bottom <= window.innerHeight;
        }

        render() {
            return (
                <div ref={this.myRef} className={this.state.isLoading ? "loading" : ""}>
                    {this.state.entries.map(
                        (entry: DataObject, i: number) =>
                            <li key={i}>
                                {
                                    molecule_mediaObject(
                                        entry.title,
                                        entry.url,
                                        "Something will go here probably",
                                        entry.photo256,
                                        entry.title
                                    )
                                }
                            </li>
                    )}
                </div>
            );
        }
    }

    const components = document.querySelectorAll('.avorg-molecule-ajaxList');

    components.forEach((el) => {
        const endpoint = el.getAttribute('data-endpoint'),
            frame = el.querySelector('.frame');

        window.wp.element.render(<AjaxList endpoint={endpoint} />, frame);
    });
}