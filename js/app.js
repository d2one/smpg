'use strict';

var React = require('react');
var ReactDOM = require('react-dom');
class Modal extends React.Component {
    render() {
        const { closeModal } = this.props;

        return (
            <div className="jumbotron" style={{position: 'absolute', width: '100%', top: 0, height: 500}}>
                <h1>Some Modal</h1>
                <button
                    className="btn btn-md btn-primary"
                    onClick={closeModal}
                >Close Modal</button>
            </div>
        )
    }
}

export class Search extends React.Component {
    constructor(props) {
        super(props);
    }

    open() {
        this.props.onModalShow(
            {
                id:this.props.data.id,
                url:this.props.data.url,
                type:this.props.data.type,
                status:this.props.data.status,
                resources_count:this.props.data.resources_count,
            }
        );
    }

    render() {
        Number.prototype.padLeft = function(base, chr){
            var  len = (String(base || 10).length - String(this).length)+1;
            return len > 0? new Array(len).join(chr || '0')+this : this;
        };
        var preparedDate = new Date(this.props.data.created_at);
        var dformat = [ (preparedDate.getMonth()+1).padLeft(),
                preparedDate.getDate().padLeft(),
                preparedDate.getFullYear()].join('/')+
            ' ' +
            [ preparedDate.getHours().padLeft(),
                preparedDate.getMinutes().padLeft(),
                preparedDate.getSeconds().padLeft()].join(':');
        var link = '/site/search/' + this.props.data.id;
        return (
            <tr className="search" >
                <td>{this.props.data.url}</td>
                <td>{dformat}</td>
                <td>{this.props.data.type}</td>
                <td>{this.props.data.status}</td>
                <td>{this.props.data.resources_count}</td>
                <td>
                    <a onClick={this.open}>show</a>
                </td>
            </tr>
        );
    }
}

export class SearchBox extends React.Component {

    constructor(props) {
        super(props)
        this.state = {data: [], modalOpen: false};
    }

    _closeModal() {
        this.state.modalOpen = false;
    }

    _openModal() {
        this.state.modalOpen = true;
    }

    loadSearchesFromServer() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: (data) => {
                if (JSON.stringify(this.state.data) !== JSON.stringify(data)) {
                    console.log('change data');
                    this.setState({data: data});
                }
            },
            error: (xhr, status, err) => {
                console.error(this.props.url, status, err.toString());
            }
        });
    }

    componentDidMount() {
        this.loadSearchesFromServer();
        setInterval(this.loadSearchesFromServer.bind(this), this.props.pollInterval);
    }

    handleCommentSubmit(search) {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: search,
            success: (data) => {
                this.loadSearchesFromServer();
            },
            error: (xhr, status, err) => {
                this.setState({data: search});
                console.error(this.props.url, status, err.toString());
            }
        });
    }

    render() {
        const { modalOpen } = this.state;
        return (
            <div className="searchBox">
                <h1>Search Panel</h1>
                <SearchForm onCommentSubmit={this.handleCommentSubmit} />
                <SearchList data={this.state.data} />
                <button
                    className="btn btn-md btn-primary"
                    onClick={this._openModal.bind(this)}
                >Open Modal</button>

                {/* Only show Modal when "this.state.modalOpen === true" */}
                {modalOpen
                    ? <Modal closeModal={this._closeModal.bind(this)} />
                    : ''}
            </div>
        );
    }
}

export class SearchList extends React.Component{
    render() {
        var commentNodes = this.props.data.map(function(search) {
            return (
                <Search data={search} key={search.id}></Search>
            );
        });
        return (
            <table className="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Site</th><th>Date</th><th>Search Type</th><th>Status</th><th>Resources Count</th><th></th>
                </tr>
                </thead>
                <tbody className="SearchList">
                {commentNodes}
                </tbody>
            </table>
        );
    }
};

export class SearchForm extends React.Component {
    validUrl(str) {
        return str.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
    }

    constructor(props) {
        super(props);
        this.state = {url: '', text: '', type: 'img', isModalOpen: false}
    }


    enableSubmitButton(arg) {
        if (arg) {
            document.getElementById('formSubmit').disabled = false
        } else {
            document.getElementById('formSubmit').disabled = true
        }
    }

    handleUrlChange(e) {
        if (this.validUrl(e.target.value)) {
            this.setState({url: e.target.value});
            this.enableSubmitButton(true);
        } else {
            this.setState({url: ''});
            this.enableSubmitButton(false);
        }
    }

    handleTypeChange(e) {
        this.setState({type: e.target.value});
        if (e.target.value == 'text') {
            this.enableSubmitButton(false);
        } else if (this.validUrl(this.state.url.trim())) {
            this.enableSubmitButton(true);
        }
    }

    handleTextChange(e) {
        if (e.target.value.length < 3) {
            this.enableSubmitButton(false);
        } else {
            this.enableSubmitButton(true);
        }
        this.setState({text: e.target.value});
    }

    handleSubmit(e) {
        e.preventDefault();
        var url = this.state.url.trim();
        var text = this.state.text.trim();
        var type = this.state.type.trim();

        if (type == 'text' && text.length < 3) {
            return;
        }

        if (!type || !url) {
            return;
        }
        this.props.onCommentSubmit({url: url, type:type, text: text});
        this.setState({url: '', text: '', type: 'img', isModalOpen: false});
    }

    render() {
        return (
            <div>
                <form className="SearchForm" onSubmit={this.handleSubmit}>
                    <div className="form-group">
                        <input
                            className="form-control"
                            type="text"
                            placeholder="Site search"
                            onChange={this.handleUrlChange}
                        />
                    </div>
                    <div className="form-group">
                        <label className="radio-inline">
                            <input type="radio" name="type" value="img" defaultChecked onChange={this.handleTypeChange}/>Img
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="type" value="link" onChange={this.handleTypeChange}/>Link
                        </label>
                        <label className="radio-inline">
                            <input type="radio" name="type" value="text" onChange={this.handleTypeChange} />Text
                        </label>
                    </div>

                    {this.state.type == 'text' ?
                        <div className="form-group">
                            <input
                                className="form-control"
                                type="text"
                                placeholder="Say something..."
                                value={this.state.text}
                                onChange={this.handleTextChange}
                            />
                        </div>
                        : null}
                    <div className="form-group">
                        <input className="btn btn-primary" id="formSubmit" type="submit" value="Find" disabled/>
                    </div>
                </form>
            </div>
        );
    }
};

ReactDOM.render(
    <SearchBox url="/api/search?sort=-created_at" pollInterval={2000} />,
    document.getElementById('content')
);