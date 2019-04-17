
import com.fasterxml.jackson.databind.JsonNode;
import org.junit.Test;
import org.tensorflow.SavedModelBundle;
import org.tensorflow.Session;
import org.tensorflow.Tensor;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;

public class SpamTester {


    @Test
    public void predictTest() {
        String path = "./python/data/model/spam";
        SpamService service = new SpamService();
        JsonNode vocabulary = DPUtil.parseJSON(FileUtil.getContent(path + "/share/vocabulary.json"));
        List<List<String>> data = Arrays.asList(
            Arrays.asList("测试"),
            Arrays.asList("青岛", "市", "办", "假", "存", "单")
        );
        Tensor inputText = Tensor.create(service.position(data, vocabulary), Integer.class);
        Tensor dropoutKeepProb = Tensor.create(1.0f, Float.class);
        SavedModelBundle bundle = SavedModelBundle.load(path + "/share", "serve");
        Session.Runner runner = bundle.session().runner();
        Tensor<?> predictions = runner.feed("input_text", inputText)
            .feed("dropout_keep_prob", dropoutKeepProb).fetch("output/predictions").run().get(0);
        long[] copy = new long[data.size()];
        predictions.copyTo(copy);
        System.out.println(Arrays.stream(copy).boxed().collect(Collectors.toList()));
    }

}
