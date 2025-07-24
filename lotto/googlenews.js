function UserDiv() {
  const [searchQuery, setSearchQuery] = React.useState('');
  const [newsData, setNewsData] = React.useState({
    topStories: [],
    world: [],
    local: [],
    business: [],
    technology: [],
    entertainment: [],
    sports: [],
    science: [],
    health: []
  });
  const categories = [
    { key: 'topStories', label: 'Top Stories' },
    { key: 'world', label: 'World' },
    { key: 'local', label: 'Local' },
    { key: 'business', label: 'Business' },
    { key: 'technology', label: 'Technology' },
    { key: 'entertainment', label: 'Entertainment' },
    { key: 'sports', label: 'Sports' },
    { key: 'science', label: 'Science' },
    { key: 'health', label: 'Health' }
  ];
  const handleSearch = async (e) => {
    e.preventDefault();
    // Call a function to fetch news based on the search query.
    // This function should be implemented to fetch data from a news API.
    console.log(`Search for news with the query: ${searchQuery}`);
  };
  const fetchNewsData = async () => {
    // This function should be implemented to fetch news data for each category.
    // For the purpose of this example, we'll pretend to fetch data and set it in state.
    console.log('Fetching news data for all categories...');
  };
  React.useEffect(() => {
    fetchNewsData();
  }, []);
  return (
    <div className="container mx-auto p-4">
      <div className="mb-4">
        <form onSubmit={handleSearch} className="flex justify-center">
          <input
            type="text"
            placeholder="Search news..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="p-2 border border-gray-300 rounded-l"
          />
          <button
            type="submit"
            className="bg-blue-500 text-white p-2 rounded-r"
          >
            Search
          </button>
        </form>
      </div>
      {categories.map((category) => (
        <div key={category.key} className="mb-8">
          <h2 className="text-2xl font-bold mb-4">{category.label}</h2>
          <div className="grid grid-cols-4 gap-4">
            {newsData[category.key].map((article, index) => (
              <div key={index} className="border p-2">
                <img src={article.image} alt={article.headline} className="w-full h-48 object-cover" />
                <h3 className="font-bold mt-2">{article.headline}</h3>
                <p>{article.summary}</p>
              </div>
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}