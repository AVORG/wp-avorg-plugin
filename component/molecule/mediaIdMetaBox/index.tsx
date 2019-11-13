import * as React from 'react';

namespace AvorgMoleculeMediaIdMetaBox {
    interface BoxState {
        newId: number,
        ids: number[]
    }

    class Box extends React.Component<{}, BoxState> {
        state: BoxState = {
            newId: null,
            ids: []
        };

        componentDidMount(): void {
            const hiddenInput: HTMLInputElement = document.querySelector('.avorg-molecule-mediaIdMetaBox__hiddenInput');

            this.setState({ids: JSON.parse(hiddenInput.value)});
        }

        makeEntry(id: number) {
            return <li data-id={id} key={id.toString()}>
                <a href={"/english/sermons/recordings/" + id} target={'_blank'}>{id}</a>
                <a href="#" className="dashicons dashicons-trash" />
            </li>;
        }

        handleAdd = (e: any) => {
            console.log(this.state.newId)
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
                    name="avorgIds"
                    value='[1,2,20690]'
                />

                <input onChange={this.handleNewIdChange} value={this.state.newId} type="text" name="avorgNewId" id="avorgNewId" placeholder="ID" />
                <button onClick={this.handleAdd}>Add</button>

                <ul>{this.state.ids.map(this.makeEntry)}</ul>
            </div>
        }
    }

    const frame: HTMLUListElement = document.querySelector('.avorg-molecule-mediaIdMetaBox');

    wp.element.render(<Box/>, frame);
}