import * as React from 'react';

namespace AvorgMoleculeMediaIdMetaBox {
    interface BoxState {
        newId: string,
        ids: number[]
    }

    interface ApiResponse {
        id: number,
        media_ids: number[]
    }

    class Box extends React.Component<{}, BoxState> {
        public readonly state: BoxState = {
            newId: '',
            ids: []
        };

        componentDidMount(): void {
            fetch('/wp-json/avorg/v1/placeholder-content/' + window.avorg.post_id)
                .then(res => res.json())
                .then((data: ApiResponse) => {
                    this.setState({
                        ids: data.media_ids || []
                    });
                });
        }

        makeEntry = (id: number) => {
            return <li data-id={id} key={id.toString()}>
                <a href={"/english/sermons/recordings/" + id} target={'_blank'}>{id}</a>
                <a onClick={(e: any) => this.removeId(id)} href="#" className="dashicons dashicons-trash" />
            </li>;
        };

        removeId = (id: number) => {
            this.setState((prev) => ({
                ids: prev.ids.filter(id_ => id_ !== id)
            }))
        };

        handleAdd = (e: any) => {
            this.setState((prev) => {
                const int = parseInt(this.state.newId);

                if (!isNaN(int)) {
                    prev.ids.push(int);
                }

                return prev;
            })
        };

        handleNewIdChange = (e: any) => {
            const el = e.target;
            this.setState((prev: BoxState) => {
                prev.newId = el.value;
                return prev;
            })
        };

        render() {
            return <div>
                <input
                    className="avorg-molecule-mediaIdMetaBox__hiddenInput"
                    type="hidden"
                    name="avorgMediaIds"
                    value={JSON.stringify(this.state.ids)}
                />

                <div className="avorg-molecule-mediaIdMetaBox__inputGroup">
                    <input onChange={this.handleNewIdChange} value={this.state.newId} type="text" name="avorgNewId" id="avorgNewId" placeholder="ID" />
                    <button onClick={this.handleAdd} className={'button button-primary'}>Add</button>
                </div>

                <ul>{this.state.ids.map(this.makeEntry)}</ul>
            </div>
        }
    }

    const frame: HTMLUListElement = document.querySelector('.avorg-molecule-mediaIdMetaBox');

    if (frame) {
        wp.element.render(<Box/>, frame);
    }
}